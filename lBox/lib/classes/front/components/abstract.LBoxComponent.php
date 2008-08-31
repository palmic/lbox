<?
/**
 * Component classes protocol
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0

 * @date 2007-12-08
 */
abstract class LBoxComponent
{
	/**
	 * regular patters for concrete purposes in components / pages
	 * @var string
	 */
	const PATTERN_HTML 			= '<[^>]*>';
	const PATTERN_URL 			= '/^.+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(([0-9]{1,5})?\/.*)?$/';

	/**
	 * @var string
	 */
	protected $templatePath 		= LBOX_PATH_TEMPLATES_COMPONENTS;

	/**
	 * array of defined slots
	 * @var array
	 */
	protected $slots = array();

	/**
	 * output content pro docasne odkladani vystupu kuli zachytavani slotu
	 * @var string
	 */
	protected $tmpContent		= "";

	/**
	 * component config node instance
	 * @var LBoxConfigItemComponent
	 */
	protected $config;

	/**
	 * reference na instanci nadrazene stranky
	 * @var Page
	 */
	protected $page;

	/**
	 * page TAL instance
	 * @var PHPTAL
	 */
	protected $TAL;

	/**
	 * set OutputFilters
	 * @var LBoxOutputFilter
	 */
	protected $outputFilter;

	/**
	 * @param string $templateFileName
	 */
	public function __construct(LBoxConfigItemComponent $config, LBoxComponent $page) {
		if ((!$page instanceof LBoxPage) && (!$page instanceof PageList)) {
			throw new LBoxExceptionFront(LBoxExceptionFront::MSG_PAGE_BAD_TYPE, LBoxExceptionFront::CODE_PAGE_BAD_TYPE);
		}
		$this->config 	= $config;
		$this->page 	= $page;
	}

	/**
	 * @param string $name
	 * @return mixed
	 * @throws LBoxExceptionFront
	 */
	public function __get($name = "") {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionFront(LBoxExceptionFront::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFront::CODE_BAD_PARAM);
			}
			switch (strtolower($name)) {
				// vraci instanci Page
				case "page":
					if (($this instanceof LBoxPage) || $this instanceof PageList) {
						return $this;
					}
					else {
						if ($this->outputFilter instanceof LBoxOutputFilter) {
							return $this->outputFilter->prepare($this, $name, $this->page);
						}
						else {
							return $this->page;
						}
					}
					break;
					// vraci isntanci LBoxConfigItemComponent
				case "config":
					return $this->config;
					break;
				default:
					// vraci parametr z configu
						return $this->config->$name;
					break;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vola se pred executePrepend - vyuziva se k nastaveni default OutPutFilteru podrazenych abstraktnich typu
	 * @param PHPTAL $TAL
	 * @throws Exception
	 */
	protected function executeStart() {
		try {
			$this->config->setOutputFilter(new OutputFilterComponent($this->config));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Funkce spoustena pred PHPTAL->execute()
	 * @param PHPTAL $TAL
	 */
	abstract protected function executePrepend(PHPTAL $TAL);

	public function setOutputFilter(LBoxOutputFilter $outputFilter) {
		$this->outputFilter = $outputFilter;
	}

	/**
	 * Getter na komponentu referencovanou v sablone
	 * @param string $id
	 * @return Component
	 * @throws Exception
	 */
	public function getComponentById($id = "") {
		try {
			if (strlen($id) < 1) {
				throw new LBoxExceptionFront(LBoxExceptionFront::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFront::CODE_BAD_PARAM);
			}
			// zvolime spravne page pro konstruktor komponenty
			if (($this instanceof LBoxPage) || $this instanceof PageList) {
				$page = $this;
			}
			else {
				$page = $this->page;
			}
			$componentCfg	= LBoxConfigManagerComponents::getInstance()->getComponentById($id);
			$className 		= $componentCfg->getClassName();
			$instance 		= new $className($componentCfg, $page);

			if (!$instance instanceof self) {
				throw new LBoxExceptionFront("Problem with component class '$className': ". LBoxExceptionFront::MSG_COMPONENT_BAD_TYPE, LBoxExceptionFront::CODE_COMPONENT_BAD_TYPE);
			}
			return $instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * startuje buffering slotu
	 * @return string
	 */
	public function templateSlotContentBegin() {
		// reinit bufferu pro slot
		$this->tmpContent	= ob_get_clean();
		return "";
	}

	/**
	 * Ukonci buffering slotu a jeho obsah ulozi do slot manageru
	 * @param string $name
	 * @return string
	 * @throws LBoxExceptionFront
	 */
	public function templateSlotContentEnd($name = "") {
		try {
			// ulozime vystup slotu
			LBoxSlotManager::getInstance()->setSlot($name, ob_get_clean());
			// puvodni obsah vyhodime zpet na vystup a vynulujeme ho v parametru
			echo $this->tmpContent;
			$this->tmpContent = NULL;
			return "";
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vrati obsah slotu podle daneho nazvu
	 * @param string $name
	 * @return string
	 * @throws LBoxExceptionFront
	 */
	public function templateGetSlot($name = "") {
		try {
			return LBoxSlotManager::getInstance()->getSlot($name);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci vystup stranky (pokud je vse ok vrati html, pokud ne vrati chybovou hlasku)
	 * @return string
	 * @throws Exception
	 */
	public function getContent() {
		try {
			$className 			= get_class($this);
			$templateFileName	= $this->getTemplateFileName();

			// prepend
			$this->executeStart($this->getTAL());
			$this->executePrepend($this->getTAL());

			try {
				$out 	 = "";
				$out	.= $this->getTAL()->execute();
				if ($this->isDebugOn()) {
					$config 			= $this->config;
					$templatePathShort	= "";
					$templatePath		= str_replace("\\", "/", $this->templatePath);
					$templatePathArr	= explode("/", $templatePath);
					$y 					= 0;
					for ($i = array_search(LBOX_DIRNAME_PROJECT, $templatePathArr); $i < count($templatePathArr); $i++) {
						if ($y > 50)break;
						$templatePathShort .= strlen($templatePathShort) > 0 ? "/" : "";
						$templatePathShort .= $templatePathArr[$i];
						$y++;
					}
					$out = "\n<!-- start of $className, with template: $templatePathShort/$templateFileName, config: $config -->\n$out\n<!-- end of $className -->\n";
				}
			}
			catch (Exception $e) {
				// var_dump($e);

				$out 	 = "";
				$out	.= "PHPTAL Exception thrown";
				$out	.= "\n";
				$out	.= "\n";
				$out	.= "code: ". nl2br($e->getCode()) ."";
				$out	.= "\n";
				$out	.= "message: ". nl2br($e->getMessage()) ."";
				$out	.= "\n";
				$out	.= "Thrown by: '". $e->getFile() ."'";
				$out	.= "\n";
				$out	.= "on line: '". $e->getLine() ."'.";
				$out	.= "\n";
				$out	.= "\n";
				$out	.= "Stack trace:";
				$out	.= "\n";
				$out	.= nl2br($e->getTraceAsString());
				// $out 	= nl2br($out) ."<hr />\n\n";
				$out 	= "<!--$out-->";
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * odstrani na vystupu HTML komentare (kontroluje prepinac funkce v system.xml)
	 * @param string $input
	 * @return string
	 */
	protected function removeComents($input = "") {
		try {
			$patterns["<!--[^\[].*-->"] = "";
			$out		= $input;
			switch (LBoxConfigSystem::getInstance()->getParamByPath("output/remove_coments")) {
				case -1:
						if (strlen(stristr(LBOX_REQUEST_URL_HOST, "localhost")) < 1) {
							foreach ((array)$patterns as $pattern => $replacement) {
								$out	= ereg_replace($pattern, $replacement, $out);
							}
						}
					break;
				case 0:
					break;
				case 1:
						foreach ((array)$patterns as $pattern => $replacement) {
							$out	= ereg_replace($pattern, $replacement, $out);
						}
					break;
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * odstrani na vystupu zbytecne znaky (kontroluje prepinac funkce v system.xml)
	 * @param string $input
	 * @return string
	 */
	protected function compress($input = "") {
		try {
			$patterns[">([[:space:]])+<"] 	= ">\\1<";
			$patterns["([[:space:]])+"] 	= "\\1";
			$patterns["[\r\n\v\f]"] 		= "";
			$out							= $input;
			switch (LBoxConfigSystem::getInstance()->getParamByPath("output/compression")) {
				case -1:
						if (strlen(stristr(LBOX_REQUEST_URL_HOST, "localhost")) < 1) {
							foreach ((array)$patterns as $pattern => $replacement) {
								$out	= ereg_replace($pattern, $replacement, $out);
							}
						}
					break;
				case 0:
					break;
				case 1:
						foreach ((array)$patterns as $pattern => $replacement) {
							$out	= ereg_replace($pattern, $replacement, $out);
						}
					break;
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter attributu TAL
	 * @return PHPTAL
	 */
	protected function getTAL() {
		if (!$this->TAL instanceof PHPTAL) {
			$this->TAL = new PHPTAL($this->templatePath ."/". $this->getTemplateFileName());
		}
		$translator	= new LBoxTranslator($this->templatePath ."/". $this->getTemplateFileName());
		$this->TAL->setTranslator($translator);
		$this->TAL->SELF = $this;
		return $this->TAL;
	}
	
	/**
	 * getter nazvu souboru se sablonou
	 * @return string
	 */
	protected function getTemplateFileName() {
		if (strlen($templateFileName = $this->config->getTemplateFileName()) < 1) {
			throw new LBoxExceptionComponent("Bad param ". 	LBoxExceptionComponent::MSG_PARAM_STRING_NOTNULL,
			LBoxExceptionComponent::CODE_BAD_PARAM);
		}
		return $templateFileName;
	}

	/**
	 * Vraci string z URL za : pokud nejaky je
	 * @return string
	 */
	protected function getUrlParamsString() {
		return LBoxFront::getUrlParamsString();
	}

	/**
	 * Vraci v poli vsechny URL params, za separator povazuje /
	 * @return string
	 */
	protected function getUrlParamsArray() {
		return LBoxFront::getUrlParamsArray();
	}

	/**
	 * vraci hodnotu aktualni stranky strankovani - pozor, atribut strankovani musi mit tvar vyhovujici vzoru nastavenem v konfiguraci system.xml
	 * @return int
	 */
	protected function getPagingCurrent() {
		try {
			$pagingUrlParamPattern = LBoxConfigSystem::getInstance()->getParamByPath("output/paging/paging_url_param_pattern");
			if (count($params = $this->getUrlParamsArray()) > 0) {
				foreach ($params as $param) {
					if (ereg($pagingUrlParamPattern, $param, $regs)) {
						// nalezneme prvni numericky reg - pro univerzalnost vzoru
						foreach ($regs as $reg) {
							if (is_numeric($reg)) {
								return (int)$reg;
							}
						}
					}
				}
				return 1;
			}
			else {
				return 1;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function isUrlParamPaging($param = "") {
		try {
			return LBoxFront::isUrlParamPaging($param);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function getPagingBy() {
		try {
			return LBoxFront::getPagingBy();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci pole stranek strankovani podle predanych parametru
	 * @param int $itemsCount - celkovy pocet strankovanych jednotek
	 * @param int $pageLimit -pocet jednotek na stranku
	 * @param int $pagesRange	-maximalni rozsah stranek v strankovani na obe strany
	 * @return array
	 * @throws LBoxExceptionPage
	 */
	protected function getPaging($itemsCount = 0, $pageLimit = 0, $pagesRange = 0) {
		if (!is_numeric($itemsCount) || $itemsCount < 1) {
			throw new LBoxExceptionPage("\$itemsCount ". LBoxExceptionPage::MSG_PARAM_INT_NOTNULL, LBoxExceptionPage::CODE_BAD_PARAM);
		}
		if (!is_numeric($pageLimit)) {
			throw new LBoxExceptionPage("\$pageLimit ". LBoxExceptionPage::MSG_PARAM_INT, LBoxExceptionPage::CODE_BAD_PARAM);
		}
		if (!is_numeric($pagesRange)) {
			throw new LBoxExceptionPage("\$pagesRange ". LBoxExceptionPage::MSG_PARAM_INT, LBoxExceptionPage::CODE_BAD_PARAM);
		}
				
		$pageLimit 				= $pageLimit 	> 0 ? $pageLimit 	: 99999;

		// sestaveni pole pages
		$out = array();
		$pagesCount = ceil($itemsCount/$pageLimit);
		if ($pagesCount < 2) {
			return NULL;
		}
		// sestaveni pole paging
		if ($pagesRange > 0) {
			$start	= ($this->getPagingCurrent() - $pagesRange < 1) ? 1 : $this->getPagingCurrent()-$pagesRange;
			$end	= $this->getPagingCurrent() + $pagesRange;
		}
		else {
			$start	= 1;
			$end	= 9999999;
		}
		for ($i = $start; $i <= $pagesCount; $i++) {
			if ($i > $end) break;
			$out[$i]	= $this->getPageURLByIndex($i);
		}
		return $out;
	}

	/**
	 * vraci pole stranek strankovani podle predanych parametru
	 * @param int $itemsCount
	 * @param int $pageLimit
	 * @param int $pagesRange
	 * @return array
	 * @throws LBoxExceptionPage
	 */
	protected function getPaging2($itemsCount = 0, $pageLimit = 0, $pagesRange	= 0) {
		if (!is_numeric($itemsCount) || $itemsCount < 1) {
			throw new LBoxExceptionPage("\$itemsCount ". LBoxExceptionPage::MSG_PARAM_INT_NOTNULL, LBoxExceptionPage::CODE_BAD_PARAM);
		}
		if (!is_numeric($pageLimit)) {
			throw new LBoxExceptionPage("\$pageLimit ". LBoxExceptionPage::MSG_PARAM_INT, LBoxExceptionPage::CODE_BAD_PARAM);
		}
		if (!is_numeric($pagesRange)) {
			throw new LBoxExceptionPage("\$pagesRange ". LBoxExceptionPage::MSG_PARAM_INT, LBoxExceptionPage::CODE_BAD_PARAM);
		}
		
		$pageLimit 				= $pageLimit 	> 0 ? $pageLimit 	: 99999;
		$pagesCount 			= ceil($itemsCount/$pageLimit);

		$pagesStart				= $this->getPagingCurrent() - $pagesRange;
		$pagesStart				= $pagesStart < 1 ? 1 : $pagesStart;
		$pagesEnd				= $this->getPagingCurrent() + $pagesRange;
		$pagesEnd				= $pagesEnd > $pagesCount ? $pagesCount : $pagesEnd;
		
		// sestaveni pole pages
		$out = array();
		if ($pagesCount < 2) {
			return NULL;
		}

		// sestaveni pole paging
		$pageCurrent	= $this->getPagingCurrent();
		if ($this->getPagingCurrent() - $pagesRange > 1) {
			$out["<<"]	= $this->getPageURLByIndex(1);
			$out["<<"]["first"]	= true;
			$out["<<"]["last"]	= false;
			$out["<<"]["class"]	= "first";
		}
		for ($i = $pagesStart; $i <= $pagesEnd; $i++) {
			$out[$i]	= $this->getPageURLByIndex($i);
			$out[$i]["first"]	= false;
			$out[$i]["last"]	= false;
			$out[$i]["class"]	= "page". ($i-$pageCurrent);
		}
		if ($this->getPagingCurrent() + $pagesRange < $pagesCount) {
			$out[">>"]	= $this->getPageURLByIndex($pagesCount);
			$out[">>"]["first"]	= false;
			$out[">>"]["last"]	= true;
			$out[">>"]["class"]	= "last";
		}
		return $out;
	}
	
	/**
	 * Vraci jednoduche strankovani (previous/next)
	 * @param int $itemsCount
	 * @param int $pageLimit
	 * @return array
	 * @throws LBoxExceptionPage
	 */
	protected function getPagingSimple($itemsCount = 0, $pageLimit = 0) {
		try {
			if (!is_numeric($itemsCount) || $itemsCount < 1) {
				throw new LBoxExceptionPage("\$itemsCount ". LBoxExceptionPage::MSG_PARAM_INT_NOTNULL, LBoxExceptionPage::CODE_BAD_PARAM);
			}
			if (!is_numeric($pageLimit)) {
				throw new LBoxExceptionPage("\$pageLimit ". LBoxExceptionPage::MSG_PARAM_INT, LBoxExceptionPage::CODE_BAD_PARAM);
			}
			if (!is_numeric($pageLimit)) {
				throw new LBoxExceptionPage("\$pagesCount ". LBoxExceptionPage::MSG_PARAM_INT, LBoxExceptionPage::CODE_BAD_PARAM);
			}
			$pageLimit 				= $pageLimit 	> 0 ? $pageLimit 	: 99999;
			$pagesCount 			= ceil($itemsCount/$pageLimit);
			$out["info"][]			= ($this->getPagingCurrent()-1) * $pageLimit + 1;
			$out["info"][]			= $this->getPagingCurrent() * $pageLimit > $itemsCount ? $itemsCount : $this->getPagingCurrent() * $pageLimit;
			$out["prevous"]			= $this->getPagingCurrent() > 1 ? $this->getPageURLByIndex($this->getPagingCurrent()-1) : "";
			$out["next"]			= $this->getPagingCurrent()*$pageLimit < $itemsCount ? $this->getPageURLByIndex($this->getPagingCurrent()+1) : "";
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vrati URL stranky podle predaneho poradi
	 * @param int $index
	 * @return string
	 * @throws Exception
	 */
	protected function getPageURLByIndex($index = 0) {
		try {
			if (!is_numeric($index) || $index < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_PARAM_INT_NOTNULL, LBoxExceptionPage::CODE_BAD_PARAM);
			}
			$pagingUrlParamPattern	= LBoxConfigSystem::getInstance()->getParamByPath("output/paging/paging_url_param_pattern");
			$pagingUrlParamExample	= LBoxConfigSystem::getInstance()->getParamByPath("output/paging/paging_url_param_example");
			$pageLimit 				= $pageLimit 					> 0 ? $pageLimit 				: 99999;
			$current 				= $this->getPagingCurrent()		> 0 ? $this->getPagingCurrent() : 1;

			// page url
			$url 			= LBOX_REQUEST_URL_VIRTUAL;
			$params 		= array();
			$paramsCount	= 0;
			$paramsLast		= NULL;
			$paramsString	= "";
			$paramPagingKey	= NULL;
			$regsPageKey	= NULL;
			if (($paramsCount = count($params = $this->getUrlParamsArray())) > 0) {
				foreach ($params as $kP => $param) {
					if (ereg($pagingUrlParamPattern, $param, $regs)) {
						// ulozime si key parametru strankovani
						$paramPagingKey	= $kP;
						// zrusime z pole prvni klic s celym stringem
						unset($regs[0]);
						// nalezneme key numerickeho parametru - stranky (abysme ho snadno mohli zamenit)
						foreach ($regs as $k => $reg) {
							if (is_numeric($reg)) {
								$regsPageKey = $k;
							}
						}
					}
				}
				// jestli jsme nasli key parametru strankovani
				if (is_numeric($paramPagingKey)) {
					// odebrat puvodni hodnotu
					unset($params[$paramPagingKey]);
				}
				// doplnime params za url
				if (count($params) > 0) {
					$paramsString	= implode("/", $params);
					$url = "$url:$paramsString";
				}
			}
			// umele sestaveni regs pro pripad, ze nemame po ruce vygenerovane z aktualniho strankovani
			if (count((array)$regs) < 1) {
				if (!ereg($pagingUrlParamPattern, $pagingUrlParamExample, $regs)) {
					throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_PAGING_URLPARAM_EXAMPLE_NOT_CORRESPOND_PATTERN, LBoxExceptionConfig::CODE_PAGING_URLPARAM_EXAMPLE_NOT_CORRESPOND_PATTERN);
				}
				// zrusime z pole prvni klic s celym stringem
				unset($regs[0]);
				// nalezneme key numerickeho parametru - stranky (abysme ho snadno mohli zamenit)
				foreach ($regs as $k => $reg) {
					if (is_numeric($reg)) {
						$regsPageKey = $k;
					}
				}
			}
			$urlGlue = count($params) > 0 ? "/" : ":";
			$pageUrlParam = "";
			// sestavime paging url param dynamicky podle patternu z configu
			foreach ((array)$regs as $kR => $reg) {
				// pokud jsme narazili na index poctu stranky, doplnime poradi stranky
				if ($kR === $regsPageKey) {
					$pageUrlParam .= $index;
				}
				// jinak pokracujeme v sestavovani stringu
				else {
					$pageUrlParam .= "$reg";
				}
			}
			// prvni stranku bez parametru pro hezkou url
			$pagePart			= $index > 1 ? $urlGlue . $pageUrlParam : "";
			$out["current"]		= ($index == $current);
			$out["url"]			= $url . $pagePart;
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * reloadne prvni stranku strankovani
	 */
	protected function reloadPagingFirstPage() {
		$url 					= LBOX_REQUEST_URL_VIRTUAL;
		$params 				= array();
		$paramsCount			= 0;
		$paramsLast				= NULL;
		$paramsString			= "";
		$pagingUrlParamPattern	= LBoxConfigSystem::getInstance()->getParamByPath("output/paging/paging_url_param_pattern");

		// odebrat hodnotu strankovani z URL params
		if (($paramsCount = count($params = $this->getUrlParamsArray())) > 0) {
			foreach ($params as $k => $param) {
				// nalezene strankovani - ulozime dany key
				if (ereg($pagingUrlParamPattern, $param, $regs)) {
					$paramPagingKey = $k;
				}
			}
			// odebereme strankovani
			unset($params[$paramPagingKey]);
			// doplnime params za url
			if (count($params) > 0) {
				$paramsString	= implode("/", $params);
				$url = "$url:$paramsString";
			}
		}
		$this->reload($url);
	}

	/**
	 * reloaduje stranku
	 * @param string $url
	 */
	protected function reload($url = "") {
		LBoxFront::reload($url);
	}

	/**
	 * Vraci config home page
	 * @return LBoxConfigItemStructure
	 * @throws LBoxException
	 */
	public function getHomePageCfg() {
		try {
			$page	= LBoxConfigManagerStructure::getInstance()->getHomePage();
			$page->setOutputFilter(new OutputFilterPage($page));
			return $page;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns if is debug on in config
	 * @return bool
	 */
	protected function isDebugOn() {
		try {
			switch (LBoxConfigSystem::getInstance()->getParamByPath("debug/components")) {
				case -1:
						return (strlen(stristr(LBOX_REQUEST_URL_HOST, "localhost")) > 0);
					break;
				case 0:
						return false;
					break;
				case 1:
						return true;
					break;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * debug given msg
	 * @param string $msg
	 */
	protected function debug($msg = "") {
		if (strlen($msg) < 1) {
			throw new LBoxExceptionComponent(LBoxExceptionComponent::MSG_PARAM_STRING_NOTNULL, LBoxExceptionComponent::CODE_BAD_PARAM);
		}
		if (!$this->isDebugOn()) return;
		echo "$msg\n";
	}

	/**
	 * Vraci group name formulare, ktere oznacuje jeho odeslana _POST data jako key pole
	 * - pro vsechny komponenty unikatni pro jasnou identifikaci
	 * - public kuli dostupnosti v sablonach
	 * je public kuli dostupnosti pro sablonu
	 * @return string
	 */
	public function getFormGroupName() {
		try {
			return $this->config->id;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>