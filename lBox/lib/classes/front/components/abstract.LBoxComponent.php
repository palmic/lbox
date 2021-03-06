<?php
/**
 * Component classes protocol
 * @author Michal Palma <michal.palma@gmail.com>
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
	 * cache var
	 * @var array
	 */
	protected $metanodesByCallnames	= array();
	
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
			// metanodes
			if (strpos(strtolower($name), "metanode") === 0) {
				return $this->getMetanodeByCallName($name);
			}
			switch (strtolower($name)) {
				// vraci instanci Page
				case "page":
					if (($this instanceof LBoxPage) || $this instanceof PageList) {
						return $this;
					}
					else {
						return $this->page;
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
	 * vola se pred ziskavanim obsahu frontem - vyuziva se k proceduram, ktere se musi provest pred manipulaci s cachi
	 * @throws Exception
	 */
	public function executeInit() {
		try {
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
			$this->addUsageToCache();
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
	 * Getter na stranku referencovanou v sablone
	 * @param int $id
	 * @return LBoxPage
	 * @throws Exception
	 */
	public function getPageById($id = "") {
		try {
			if (strlen($id) < 1) {
				throw new LBoxExceptionFront(LBoxExceptionFront::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFront::CODE_BAD_PARAM);
			}
			$config		= LBoxConfigManagerStructure::getInstance()->getPageById($id);
			$config		->setOutputFilter(new OutputFilterPage($config));
			$pageClass	= $config->class;
			$page		= new $pageClass($config);
			return $page;
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
		$this->tmpContent	= ob_get_contents();
		ob_clean();
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
			LBoxSlotManager::getInstance()->setSlot($name, ob_get_contents());
			ob_clean();
			// puvodni obsah vyhodime zpet na vystup a vynulujeme ho v parametru
			echo $this->tmpContent;
			$this->tmpContent = "";
			return "";
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vrati obsah slotu podle daneho nazvu
	 * @param string $name
	 * @param bool $silent
	 * @return string
	 * @throws LBoxExceptionFront
	 */
	public function templateGetSlot($name = "", $silent = false) {
		try {
			return LBoxSlotManager::getInstance()->getSlot($name, $silent);
		}
		catch (Exception $e) {
			if ($silent && $e->getCode() == LBoxExceptionFront::CODE_SLOT_NOT_DEFINED) {
				return "";
			}
			else {
				throw $e;
			}
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
			
			$out	= $this->combineJS($out);
			
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
			$out		= $input;
			
			$pattern	= "/<!--(.|\s)*?-->/";
			// parseout html header
			$regBody	= "<body((\s+\w+(\s*=\s*(?:\".*?\"|'.*?'|[^'\">\s]+))?)+\s*|\s*)>(.*)<\/body>";
			$retBody	= preg_match_all("/$regBody/si", $out, $regsBody);
			$bodyNew	= $body = reset(reset($regsBody));
			switch (LBoxConfigSystem::getInstance()->getParamByPath("output/remove_coments")) {
				case -1:
					if (LBOX_REQUEST_IP != "127.0.0.1") {
						$bodyNew	= preg_replace($pattern, "", $bodyNew);
					}
					break;
				case 0:
					break;
				case 1:
					$bodyNew	= preg_replace($pattern, "", $bodyNew);
					break;
			}
			$out	= str_replace($body, $bodyNew, $out);
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
			$pattern	= "/[\n\r|\r\n|\n|\r|\t| ]+/";
			$out		= $input;
			switch (LBoxConfigSystem::getInstance()->getParamByPath("output/compression")) {
				case -1:
						if (LBOX_REQUEST_IP != "127.0.0.1") {
							$out	= preg_replace($pattern, " ", $out);
						}
					break;
				case 0:
					break;
				case 1:
						$out	= preg_replace($pattern, " ", $out);
					break;
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	* kombinuje externi JS do jednoho verzovaneho a nahrazuje v hlavickach jejich includy
	* @param string $input
	* @return string
	*/
	protected function combineJS($input = "") {
		try {
			switch (LBoxConfigSystem::getInstance()->getParamByPath("output/js_combine")) {
				case 0:
					return $input;
				break;
				case -1:
					if (LBOX_REQUEST_IP == "127.0.0.1") {
						return $input;
					}
				break;
			}
			$out		= $input;

			// parseout html header
			$regHeader	= "<head((\s+\w+(\s*=\s*(?:\".*?\"|'.*?'|[^'\">\s]+))?)+\s*|\s*)>(.*)<\/head>";
			$retHeader	= preg_match_all("/$regHeader/si", $out, $regsHeader);
			$header		= reset(reset($regsHeader));
			// once combined code check
			if (strpos($header, str_replace(LBOX_PATH_PROJECT, "", LBoxFilesCombineJS::getInstance()->getPathDestination()))) {
				return $input;
			}

			// get header javascripts
			$template	= '<script type="text/javascript" src="<src>"></script>';
			$reg		= $template;
			$reg		= str_ireplace("/", '\\/', $reg);
			$reg		= str_ireplace("<src>", '(.[^>]*)', $reg);
			$reg		= "/$reg/si";
			$files	= array();
			$ret = preg_match_all($reg, $header, $regs);
			foreach ($regs[1] as $k => $reg) {
				// only local files
				if (preg_match("/^http(s)?:/", $reg)) {
					unset($regs[0][$k]);
					unset($regs[1][$k]);
					continue;
				}
				$files[]	= $reg;
			}
//LBoxFirePHP::log($files);
			if (count($files) > 0) {
				$i	= 1;
				foreach ($regs[0] as $reg) {
					$replace	= $i == count($regs[0]) ? str_ireplace("<src>", LBoxFilesCombineJS::getInstance()->combine($files), $template) : "";
//LBoxFirePHP::log($reg);
//LBoxFirePHP::warn($replace);
					$out = str_replace($reg, $replace, $out);
					$i++;
				}
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter TAL objektu
	 * @return PHPTAL
	 */
	protected function getTAL() {
		try {
			$pathTemplate	= LBoxUtil::fixPathSlashes($this->getTemplatePath());
			if (!$this->TAL instanceof PHPTAL) {
				$this->TAL = new PHPTAL($pathTemplate);
			}
			$translator	= new LBoxTranslator($pathTemplate);
			// zajistit existenci ciloveho adresare PHP kodu pro TAL:
			$phptalPhpCodeDestination	= LBoxUtil::fixPathSlashes(LBoxConfigSystem::getInstance()->getParamByPath("output/tal/PHPTAL_PHP_CODE_DESTINATION"));
			LBoxUtil::createDirByPath($phptalPhpCodeDestination);
			$this->TAL->setTranslator($translator);
			$this->TAL->setForceReparse(LBoxConfigSystem::getInstance()->getParamByPath("output/tal/PHPTAL_FORCE_REPARSE"));
			$this->TAL->setPhpCodeDestination($phptalPhpCodeDestination);
			$this->TAL->SELF = $this;
			return $this->TAL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na cestu k sablone
	 * @return string
	 */
	protected function getTemplatePath() {
		try {
			return LBoxUtil::fixPathSlashes($this->templatePath ."/". $this->getTemplateFileName());
		}
		catch (Exception $e) {
			throw $e;
		}
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
					if (preg_match("/$pagingUrlParamPattern/", $param, $regs)) {
					//if (ereg($pagingUrlParamPattern, $param, $regs)) {
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
		if (!is_numeric($itemsCount)) {
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
		if (!is_numeric($itemsCount)) {
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
	 * vraci pole stranek strankovani podle predanych parametru
	 * @param int $itemsCount
	 * @param int $pageLimit
	 * @param int $pagesRange
	 * @return array
	 * @throws LBoxExceptionPage
	 */
	protected function getPaging3($itemsCount = 0, $pageLimit = 0, $pagesRange	= 0) {
		try {
			if (!is_numeric($itemsCount)) {
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
			$pageCurrent		= $this->getPagingCurrent();
			$pagePreviousKey	= $pageCurrent > 1 ? $pageCurrent-1 : 1;
			$pageNextKey		= $pageCurrent+1;
			$out["<<"]	= $this->getPageURLByIndex($pagePreviousKey);
			$out["<<"]["first"]		= true;
			$out["<<"]["active"]	= $this->getPagingCurrent() > 1;
			$out["<<"]["key"]		= $pagePreviousKey;
			$out["<<"]["last"]		= false;
			$out["<<"]["class"]		= "first";
			for ($i = $pagesStart; $i <= $pagesEnd; $i++) {
				$out[$i]	= $this->getPageURLByIndex($i);
				$out[$i]["active"]	= !$out[$i]["current"];
				$out[$i]["key"]		= $i;
				$out[$i]["first"]	= false;
				$out[$i]["last"]	= false;
				$out[$i]["class"]	= "page". ($i-$pageCurrent);
			}
			$out[">>"]	= $this->getPageURLByIndex($pageNextKey);
			$out[">>"]["first"]		= false;
			$out[">>"]["active"]	= $this->getPagingCurrent() < $pagesCount;
			$out[">>"]["key"]		= $pageNextKey;
			$out[">>"]["last"]		= true;
			$out[">>"]["class"]		= "last";

			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci pole stranek strankovani podle predanych parametru
	 * @param int $itemsCount
	 * @param int $pageLimit
	 * @param int $pagesRange
	 * @return array
	 * @throws LBoxExceptionPage
	 */
	protected function getPaging4($itemsCount = 0, $pageLimit = 0, $pagesRange	= 0) {
		try {
			if (!is_numeric($itemsCount)) {
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
			$pageCurrent		= $this->getPagingCurrent();
			$pagePreviousKey	= $pageCurrent > 1 ? $pageCurrent-1 : 1;
			$pageNextKey		= $pageCurrent+1;
			$out["<<"]			= $this->getPageURLByIndex($pageCurrent > 1 ? $pageCurrent-1 : 1);
			$out["<<"]["first"]		= true;
			$out["<<"]["active"]	= $pageCurrent != 1;
			$out["<<"]["key"]		= $pageCurrent-1;
			$out["<<"]["last"]		= false;
			$out["<<"]["class"]		= "first";
			if (abs($pagesStart-$pageCurrent) > 3) {
				for ($i = 1; $i <= 2; $i++) {
					$out[$i]	= $this->getPageURLByIndex($i);
					$out[$i]["active"]	= !$out[$i]["current"];
					$out[$i]["key"]		= $i;
					$out[$i]["first"]	= false;
					$out[$i]["last"]	= false;
					$out[$i]["class"]	= "pagefrom";
				}
			}
			for ($i = $pagesStart; $i <= $pagesEnd; $i++) {
				if ($i < $pageCurrent) {
					if ($pageCurrent <= $pagesRange+1) {
						$out[$i]	= $this->getPageURLByIndex($i);
						$out[$i]["active"]	= !$out[$i]["current"];
						$out[$i]["key"]		= $i;
						$out[$i]["first"]	= false;
						$out[$i]["last"]	= false;
						$out[$i]["class"]	= "page". ($i-$pageCurrent);
					}
					else {
						if (abs($i-$pageCurrent) == $pagesRange-2) {
							$out[$i]["current"]	= false;
							$out[$i]["url"]		= "";
							$out[$i]["active"]	= false;
							$out[$i]["key"]		= "...";
							$out[$i]["etc"]		= true;
							$out[$i]["first"]	= false;
							$out[$i]["last"]	= false;
							$out[$i]["class"]	= "pageetc";
						}
						else if (abs($i-$pageCurrent) < $pagesRange-2) {
							$out[$i]	= $this->getPageURLByIndex($i);
							$out[$i]["active"]	= !$out[$i]["current"];
							$out[$i]["key"]		= $i;
							$out[$i]["first"]	= false;
							$out[$i]["last"]	= false;
							$out[$i]["class"]	= "page". ($i-$pageCurrent);
						}
						else if (($i < $pageCurrent && $pageCurrent < 1+$pagesRange) || ($i > $pageCurrent && $pagesCount-$pageCurrent < $pagesRange)) {
							$out[$i]	= $this->getPageURLByIndex($i);
							$out[$i]["active"]	= !$out[$i]["current"];
							$out[$i]["key"]		= $i;
							$out[$i]["first"]	= false;
							$out[$i]["last"]	= false;
							$out[$i]["class"]	= "pagefrom";
						}
					}
				}
				else if ($i > $pageCurrent) {
					if ($pagesCount-$pageCurrent <= $pagesRange) {
						$out[$i]	= $this->getPageURLByIndex($i);
						$out[$i]["active"]	= !$out[$i]["current"];
						$out[$i]["key"]		= $i;
						$out[$i]["first"]	= false;
						$out[$i]["last"]	= false;
						$out[$i]["class"]	= "page". ($i-$pageCurrent);
					}
					else {
						if (abs($i-$pageCurrent) == $pagesRange-2) {
							$out[$i]["current"]	= false;
							$out[$i]["url"]		= "";
							$out[$i]["active"]	= false;
							$out[$i]["key"]		= "...";
							$out[$i]["etc"]		= true;
							$out[$i]["first"]	= false;
							$out[$i]["last"]	= false;
							$out[$i]["class"]	= "pageetc";
						}
						else if (abs($i-$pageCurrent) < $pagesRange-2) {
							$out[$i]	= $this->getPageURLByIndex($i);
							$out[$i]["active"]	= !$out[$i]["current"];
							$out[$i]["key"]		= $i;
							$out[$i]["first"]	= false;
							$out[$i]["last"]	= false;
							$out[$i]["class"]	= "page". ($i-$pageCurrent);
						}
						else if (($i < $pageCurrent && $pageCurrent < 1+$pagesRange) || ($i > $pageCurrent && $pagesCount-$pageCurrent < $pagesRange)) {
							$out[$i]	= $this->getPageURLByIndex($i);
							$out[$i]["active"]	= !$out[$i]["current"];
							$out[$i]["key"]		= $i;
							$out[$i]["first"]	= false;
							$out[$i]["last"]	= false;
							$out[$i]["class"]	= "pageto";
						}
					}
				}
				else {
					$out[$i]	= $this->getPageURLByIndex($i);
					$out[$i]["active"]	= $i == $pagec;
					$out[$i]["key"]		= $i;
					$out[$i]["first"]	= false;
					$out[$i]["last"]	= false;
					$out[$i]["class"]	= "page". ($i-$pageCurrent);
				}
			}
			if (abs($pagesCount-$pageCurrent) > 3) {
				for ($i = $pagesCount-1; $i <= $pagesCount; $i++) {
					$out[$i]	= $this->getPageURLByIndex($i);
					$out[$i]["active"]	= !$out[$i]["current"];
					$out[$i]["key"]		= $i;
					$out[$i]["first"]	= false;
					$out[$i]["last"]	= false;
					$out[$i]["class"]	= "page". ($i-$pageCurrent);
				}
			}
			$out[">>"]				= $this->getPageURLByIndex($pageCurrent+1);
			$out[">>"]["first"]		= false;
			$out[">>"]["active"]	= $pageCurrent != $pagesCount;
			$out[">>"]["key"]		= $pageCurrent+1;
			$out[">>"]["last"]		= true;
			$out[">>"]["class"]		= "last";

			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
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
			if (!is_numeric($itemsCount)) {
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
			$queryString	= strlen(LBOX_REQUEST_URL_QUERY) > 0 ? LBOX_REQUEST_URL_QUERY : "";

			if (($paramsCount = count($params = $this->getUrlParamsArray())) > 0) {
				foreach ($params as $kP => $param) {
					if (preg_match("/$pagingUrlParamPattern/", $param, $regs)) {
					//if (ereg($pagingUrlParamPattern, $param, $regs)) {
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
				if (!preg_match("/$pagingUrlParamPattern/", $pagingUrlParamExample, $regs)) {
				//if (!ereg($pagingUrlParamPattern, $pagingUrlParamExample, $regs)) {
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
			$queryStringGlue	= strlen($queryString) > 0 ? "?" : "";
			$pagePart			= $index > 1 ? $urlGlue . $pageUrlParam : "";
			$out["current"]		= ($index == $current);
			$out["url"]			= $url . $pagePart . $queryStringGlue . $queryString;
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
				if (preg_match("/$pagingUrlParamPattern/", $param, $regs)) {
				//if (ereg($pagingUrlParamPattern, $param, $regs)) {
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
			if (LBoxFront::getPage()->config->getOutputFilter() instanceof LBoxOutputFilter) {
				$outputFilterClassName	= get_class(LBoxFront::getPage()->config->getOutputFilter());
				$page->setOutputFilter(new $outputFilterClassName($page));
			}
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
						return (LBOX_REQUEST_IP == "127.0.0.1");
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
	
	/**
	* getter na metanode
	* @param string $callname
	* return LBoxMetanode
	*/
	protected function getMetanodeByCallName($callname = "") {
		try {
			//LBoxFirePHP::log($callname);
			
			if (array_key_exists($callname, $this->metanodesByCallnames) && $this->metanodesByCallnames[$callname] instanceof LBoxMetanode) {
				// dalsi instance metanodu v komponente / strance zobrazime uz jen pasivni
				$this->metanodesByCallnames[$callname]->setActive(false);
				return $this->metanodesByCallnames[$callname];
			}
			$parts	= explode("_", $callname);
			if (count($parts) < 3) {
				throw new LBoxExceptionComponent("Wrong metanode callname '$callname'", LBoxExceptionComponent::CODE_BAD_PARAM);
			}
			$seq						= (int)$parts[1];
			$type						= $parts[2];
			$this->metanodesByCallnames[$callname]	= LBoxMetanodeManager::getNode($type, $seq, $this);

			return $this->metanodesByCallnames[$callname];
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * public getter na metanode
	 * @param int $id
	 * @param string $type
	 * @return LBoxMetanode
	 */
	public function getMetanode($id, $type = "") {
		try {
			return $this->getMetanodeByCallName("metanode_". $id ."_". $type);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * zaindexuje sam sebe ve front cachi jako pouzity na aktualni strance
	 */
	protected function addUsageToCache() {
		try {
			LBoxCacheManagerFront::getInstance()->addComponentUsed($this);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>