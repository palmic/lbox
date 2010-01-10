<?
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @date 2009-06-06
 */
abstract class LBoxMetanode extends LBox
{
	/**
	 * caller instance
	 * @var LBoxComponent
	 */
	protected $caller;
	
	/**
	 * items seq
	 * @var int
	 */
	protected $seq;
	
	/**
	 * data lng
	 * @var string
	 */
	protected $lng;
	
	/**
	 * file pointer cache
	 * @var resource
	 */
	protected $fileH;
	
	/**
	 * styles file pointer cache
	 * @var resource
	 */
	protected $fileStylesH;
	
	/**
	 * data cache
	 * @var string
	 */
	protected $content = "";
	
	/**
	 * data cache
	 * @var string
	 */
	protected $styles = array();
	
	/**
	 * data filename ext
	 * @var string
	 */
	protected $ext	= "html";
	
	/**
	 * path cache
	 * @var string
	 */
	protected $path	= "";
	
	/**
	 * styles path cache
	 * @var string
	 */
	protected $pathStyles	= "";
	
	/**
	 * paths cached by caller types
	 * @var array
	 */
	protected static $pathsByCallerTypes	= array();
	
	/**
	 * paths cached by caller types
	 * @var array
	 */
	protected static $pathsStylesByCallerTypes	= array();
	
	/**
	 * flag
	 * @var bool
	 */
	protected $contentChanged = false;

	/**
	 * flag
	 * @var bool
	 */
	protected $stylesChanged = false;
	
	/**
	 * cache var
	 * @var LBoxForm
	 */
	protected $form;
	
	/**
	 * cache var
	 * @var string
	 */
	protected $out	= "";
	
	/**
	 * is passive flag
	 * @var bool
	 */
	protected $isActive;
	
	CONST TYPE								= "";

	CONST TEMPLATE_FILENAME					= "";
	
	public function __construct($seq = 0, LBoxComponent $caller, $lng = "") {
		if (!is_int($seq) || $seq < 1) {
			throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_PARAM_INT_NOTNULL, LBoxExceptionMetanodes::CODE_BAD_PARAM);
		}
		$this->seq		= $seq;
		$this->lng		= strlen($lng) > 0 ? $lng : LBoxFront::getDisplayLanguage();
		$this->caller	= $caller;
		
		//init data
		$this->getFileH();
		if (filesize($this->getFilePath()) > 0) {
			if (($this->content	= fread($this->getFileH(), filesize($this->getFilePath()))) === false) {
				throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_DATA_CANNOT_READ, LBoxExceptionMetanodes::CODE_DATA_CANNOT_READ);
			}
		}
		$this->getFileStylesH();
		if (filesize($this->getFileStylesPath()) > 0) {
//LBoxFirePHP::log("init styles file data: ". fread($this->getFileStylesH(), filesize($this->getFileStylesPath())));
			if (($this->styles	= unserialize(fread($this->getFileStylesH(), filesize($this->getFileStylesPath())))) === false) {
				throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_DATA_CANNOT_READ, LBoxExceptionMetanodes::CODE_DATA_CANNOT_READ);
			}
		}
/*$callerType	= get_class($this->caller) == "LBoxPage" ? "page" : "component";
LBoxFirePHP::table($this->styles, basename(__FILE__) ."::". __LINE__.': ' . 'metanode '. $callerType .'-'. $this->caller->id .'-'. $this->seq .' init styles');*/
	}
	
	/**
	* toString converter
	* @return string
	*/
	public function __toString() {
		try {
			if (strlen($this->out) < 1) {
				try {
					$this->out 	 = "";
					$this->out	.= $this->getTAL()->execute();
				}
				catch (Exception $e) {
					// var_dump($e);
	
					$this->out 	 = "";
					$this->out	.= "PHPTAL Exception thrown";
					$this->out	.= "\n";
					$this->out	.= "\n";
					$this->out	.= "code: ". nl2br($e->getCode()) ."";
					$this->out	.= "\n";
					$this->out	.= "message: ". nl2br($e->getMessage()) ."";
					$this->out	.= "\n";
					$this->out	.= "Thrown by: '". $e->getFile() ."'";
					$this->out	.= "\n";
					$this->out	.= "on line: '". $e->getLine() ."'.";
					$this->out	.= "\n";
					$this->out	.= "\n";
					$this->out	.= "Stack trace:";
					$this->out	.= "\n";
					$this->out	.= nl2br($e->getTraceAsString());
					// $this->out 	= nl2br($this->out) ."<hr />\n\n";
					$this->out 	= "<!--". $this->out ."-->";
				}
			}
			return $this->out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function __destruct() {
		@fclose($this->fileH);
	}
	
	/**
	* getter na obsah
	* @return string
	*/
	public function getContent() {
		try {
//FirePHP::getInstance(true)->log('metanode '. get_class($this->caller) == "LBoxPage" ? "page" : "component" .'-'. $this->caller->id .'-'. $this->seq .' get data: '. $this->content);
			return $this->content;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	* getter na styly
	* @return array
	*/
	public function getStyles() {
		try {
//LBoxFirePHP::table($this->styles, 'metanode '. get_class($this->caller) == "LBoxPage" ? "page" : "component" .'-'. $this->caller->id .'-'. $this->seq .' getStyles');
			return $this->styles;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na styly CSS
	 * @return string
	 */
	public function getStylesCSS() {
		try {
			$out	= "";
//LBoxFirePHP::table($this->styles, 'metanode '. get_class($this->caller) == "LBoxPage" ? "page" : "component" .'-'. $this->caller->id .'-'. $this->seq .' getStylesCSS styles');
			foreach ($this->getStyles() as $property => $value) {
				$out	.= "$property:$value;";
			}
//LBoxFirePHP::log('metanode '. get_class($this->caller) == "LBoxPage" ? "page" : "component" .'-'. $this->caller->id .'-'. $this->seq .' getStylesCSS: '. $out);
			return $out;
		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * public getter na seq
	 * @return int
	 */
	public function getSeq() {
		try {
			return (int)$this->seq;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * public getter na claller id
	 * @return string
	 */
	public function getCallerID() {
		try {
			return (string)$this->caller->id;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	* setter active flagu
	* @param bool $value
	*/
	public function setActive($value = true) {
		try {
			if (!is_bool($value)) {
				throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_PARAM_BOOL, LBoxExceptionMetanodes::CODE_BAD_PARAM);
			}
			// k tomu, aby se zmenil vystup, ho musime pregenerovat!
			$this->out	= "";
			$this->isActive	= $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * jestli ma byt metanode aktivni, nebo ne (pro AJAX editaci)
	 * @return bool
	 */
	public function isActive() {
		try {
			if (is_bool($this->isActive)) {
				return $this->isActive;
			}
			/* prihlaseni uzivatele kontrolujeme dvema zpusoby
			 * 1) zkontrolujeme LBoxXTDBFree, coz bylo zrizeno specialne pro metanodes
			 * 2) potom se mrkneme jeste na LBoxXTProject::isLoggedAdmin() - priorita #2
			*/
			if (LBoxXTDBFree::isLogged()) {
				return $this->isActive = true;
			}
			else {
				return $this->isActive = LBoxXTProject::isLoggedAdmin();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na editacni form pro AJAX GUI
	 * @return LBoxForm
	 */
	public function getForm() {
		try {
			if (!$this->isActive()) {
				return NULL;
			}
			if ($this->form instanceof LBoxForm) {
				return $this->form;
			}
			$type					= get_class($this);
			$nodeType				= eval("return $type::TYPE;");
			$nodeControlClassName	= eval("return $type::XT_FORM_CTRL_CLASSNAME;");
			$nodeControlTemplate	= eval("return $type::XT_FORM_CTRL_TEMPLATE_FILENAME;");
			$seq					= $this->seq;
			$callerID				= $this->caller->id;
			$formID					= "metanode-$callerID-$seq";
			$ctrlType				= new LBoxFormControlFillHidden("type", "", $nodeType);
				$ctrlType			->setTemplateFileName("metanode_hidden.html");
			$ctrlSeq				= new LBoxFormControlFillHidden("seq", "", $seq);
				$ctrlSeq			->setTemplateFileName("metanode_hidden.html");
			$ctrlCallerID			= new LBoxFormControlFillHidden("caller_id", "", $callerID);
				$ctrlCallerID		->setTemplateFileName("metanode_hidden.html");
			$ctrlCallerType			= new LBoxFormControlFillHidden("caller_type", "", $this->caller instanceof LBoxPage ? "page" : "component");
				$ctrlCallerType		->setTemplateFileName("metanode_hidden.html");
			$ctrlLng				= new LBoxFormControlFillHidden("lng", "", LBoxFront::getDisplayLanguage());
				$ctrlLng			->setTemplateFileName("metanode_hidden.html");
			$ctrlContent		= new $nodeControlClassName("content", "", $this->getContent());
				$ctrlContent		->setTemplateFileName($nodeControlTemplate);

			// vlozime ho do dialog boxu pro JS GUI
			$ctrlDialog				= new LBoxFormControlMultiple("dialog", "");
			$ctrlDialog				->setTemplateFileName("metanode_dialog.html");
			$ctrlDialog				->addControl($ctrlContent);
			$this->form					= new LBoxForm($formID, "post", "", "editovat");
			$this->form					->setTemplateFileName("metanode_xt_toedit.html");
			$this->form->action			= LBoxConfigSystem::getInstance()->getParamByPath("metanodes/api/url");
			$this->form					->addControl($ctrlDialog);
			$this->form					->addProcessor(new ProcessorMetanodeXTToEdit);
			$this->form->className		= "to-edit";

			$this->form					->addControl($ctrlType);
			$this->form					->addControl($ctrlSeq);
			$this->form					->addControl($ctrlCallerID);
			$this->form					->addControl($ctrlCallerType);
			$this->form					->addControl($ctrlLng);
			
			return $this->form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	* setter obsahu
	* @param string $content
	*/
	public function setContent($content = "") {
		try {
			$this->content	= $content;
			$this->contentChanged	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	* setter stylu
	* @param array $styles
	*/
	public function setStyles($styles = array()) {
		try {
			if ((!is_array($styles)) || count($styles) < 1) {
				throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_PARAM_ARRAY_NOTNULL, LBoxExceptionMetanodes::CODE_BAD_PARAM);
			}
//LBoxFirePHP::table($this->styles, basename(__FILE__) ."::". __LINE__.': ' . "styles before set");
//LBoxFirePHP::table($styles, basename(__FILE__) ."::". __LINE__.': ' . "styles to set");
			$this->styles	= array_merge($this->styles, $styles);
//LBoxFirePHP::table($this->styles, basename(__FILE__) ."::". __LINE__.': ' . "styles after set");
			$this->stylesChanged	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	* resetter stylu
	* @param array $styles
	*/
	public function resetStyles($styles = array()) {
		try {
			if ((!is_array($styles)) || count($styles) < 1) {
				throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_PARAM_ARRAY_NOTNULL, LBoxExceptionMetanodes::CODE_BAD_PARAM);
			}
			$this->styles	= $styles;
			$this->stylesChanged	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * stores the data
	 */
	public function store() {
		try {
			//content
			// drop previous content cache
			if ($this->contentChanged) {
/*$nodeType = $this->caller instanceOf LBoxPage ? "page" : "component";
LBoxFirePHP::log(basename(__FILE__) ."::". __LINE__.': ' . 'storing the data of metanode: '. $nodeType .'-'. $this->caller->id .'-'. $this->seq);*/
				unset($this->fileH);
				@unlink($this->getFilePath());
				if (strlen($this->content) > 0) {
//LBoxFirePHP::log(basename(__FILE__) ."::". __LINE__.': ' . "saving the content data ". $this->content ." into file ". $this->getFilePath());
					if (!fwrite($this->getFileH(), $this->content)) {
						throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_DATA_CANNOT_WRITE, LBoxExceptionMetanodes::CODE_DATA_CANNOT_WRITE);
					}
//LBoxFirePHP::log(basename(__FILE__) ."::". __LINE__.': ' . "saving content done");
				}
			}
			//styles
			if ($this->stylesChanged) {
//LBoxFirePHP::log(basename(__FILE__) ."::". __LINE__.': ' . "deleting styles file ". $this->getFileStylesPath());
				fclose($this->getFileStylesH());
				unlink($this->getFileStylesPath());
				if (count($this->styles) > 0) {
//LBoxFirePHP::table($this->styles, basename(__FILE__) ."::". __LINE__.': ' . "saving the styles data into file ". $this->getFileStylesPath());
					if (!fwrite($this->getFileStylesH(), serialize($this->styles))) {
						throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_DATA_CANNOT_WRITE, LBoxExceptionMetanodes::CODE_DATA_CANNOT_WRITE);
					}
//LBoxFirePHP::log(basename(__FILE__) ."::". __LINE__.': ' . "saving styles done");
				}
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na file pointer
	 * @return resource
	 */
	protected function getFileH() {
		try {
			if (!file_exists($this->getFilePath())) {
//LBoxFirePHP::log(basename(__FILE__) ."::". __LINE__.': ' . "creating new styles file ". $this->getFileStylesPath());
				$temp = fopen($this->getFilePath(), "a+");
				fclose($temp);
			}
			else {
//LBoxFirePHP::log(basename(__FILE__) ."::". __LINE__.': ' . "styles file exists: ". $this->getFileStylesPath());
			}
			if (!$this->fileH	= fopen($this->getFilePath(), "a+")) {
				throw new LBoxExceptionMetanodes($this->getFilePath() .": ". LBoxExceptionMetanodes::MSG_DATA_CANNOT_OPEN_WRITE, LBoxExceptionMetanodes::CODE_DATA_CANNOT_OPEN_WRITE);
			}
			fseek($this->fileH, 0);
			return $this->fileH;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na file pointer
	 * @return resource
	 */
	protected function getFileStylesH() {
		try {
			if (!file_exists($this->getFileStylesPath())) {
				$temp = fopen($this->getFileStylesPath(), "a+");
				fclose($temp);
			}
			if (!$this->fileStylesH	= fopen($this->getFileStylesPath(), "a+")) {
				throw new LBoxExceptionMetanodes($this->getFileStylesPath() .": ". LBoxExceptionMetanodes::MSG_DATA_CANNOT_OPEN_WRITE, LBoxExceptionMetanodes::CODE_DATA_CANNOT_OPEN_WRITE);
			}
			fseek($this->fileStylesH, 0);
			return $this->fileStylesH;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	* getter na cestu k souboru s obsahem
	* @return string
	*/
	protected function getFilePath() {
		try {
			if (strlen($this->path) > 0) {
				return $this->path;
			}
			$this->path	= self::getPathByCallerType($this->caller instanceof LBoxPage ? "pages" : "components");
			$this->path	= str_replace("\$caller_id", $this->caller->config->id, $this->path);
			$this->path	= str_replace("\$seq", $this->seq, $this->path);
			$this->path	= str_replace("\$ext", $this->ext, $this->path);
			$this->path	= str_replace("\$lng", $this->lng, $this->path);
			
			$this->path	= LBoxUtil::fixPathSlashes($this->path);
			LBoxUtil::createDirByPath(dirname($this->path));
			return $this->path;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	* getter na cestu k souboru se styly
	* @return string
	*/
	protected function getFileStylesPath() {
		try {
			if (strlen($this->pathStyles) > 0) {
				return $this->pathStyles;
			}
			$this->pathStyles	= self::getPathStylesByCallerType($this->caller instanceof LBoxPage ? "pages" : "components");
			$this->pathStyles	= str_replace("\$caller_id", $this->caller->config->id, $this->pathStyles);
			$this->pathStyles	= str_replace("\$seq", $this->seq, $this->pathStyles);
			$this->pathStyles	= str_replace("\$lng", $this->lng, $this->pathStyles);
			
			$this->pathStyles	= LBoxUtil::fixPathSlashes($this->pathStyles);
			LBoxUtil::createDirByPath(dirname($this->pathStyles));
			return $this->pathStyles;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	* vraci predparserovanou hodnotu parametru z configu - proti opakovanemu parserovani
	* @param string $type
	* @return string
	*/
	protected static function getPathByCallerType($type = "") {
		try {
			if (strlen($type) < 1) {
				throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_PARAM_STRING_NOTNULL, LBoxExceptionMetanodes::CODE_BAD_PARAM);
			}
			if (array_key_exists($type, self::$pathsByCallerTypes)) {
				return self::$pathsByCallerTypes[$type];
			}
			self::$pathsByCallerTypes[$type]	= LBoxConfigSystem::getInstance()->getParamByPath("metanodes/data/content/path");
			self::$pathsByCallerTypes[$type]	= str_replace("\$caller_type", $type, self::$pathsByCallerTypes[$type]);
			return self::$pathsByCallerTypes[$type];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	* vraci predparserovanou hodnotu parametru z configu - proti opakovanemu parserovani
	* @param string $type
	* @return string
	*/
	protected static function getPathStylesByCallerType($type = "") {
		try {
			if (strlen($type) < 1) {
				throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_PARAM_STRING_NOTNULL, LBoxExceptionMetanodes::CODE_BAD_PARAM);
			}
			if (array_key_exists($type, self::$pathsStylesByCallerTypes)) {
				return self::$pathsStylesByCallerTypes[$type];
			}
			self::$pathsStylesByCallerTypes[$type]	= LBoxConfigSystem::getInstance()->getParamByPath("metanodes/data/styles/path");
			self::$pathsStylesByCallerTypes[$type]	= str_replace("\$caller_type", $type, self::$pathsStylesByCallerTypes[$type]);
			return self::$pathsStylesByCallerTypes[$type];
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
		try {
			$templatePath	= LBoxConfigSystem::getInstance()->getParamByPath("metanodes/templates/path");
			if (!$this->TAL instanceof PHPTAL) {
				$this->TAL = new PHPTAL($templatePath ."/". $this->getTemplateFileName());
			}
			// zajistit existenci ciloveho adresare PHP kodu pro TAL:
			$phptalPhpCodeDestination	= LBoxUtil::fixPathSlashes(LBoxConfigSystem::getInstance()->getParamByPath("output/tal/PHPTAL_PHP_CODE_DESTINATION"));
			LBoxUtil::createDirByPath($phptalPhpCodeDestination);
			$translator	= new LBoxTranslator($templatePath ."/". $this->getTemplateFileName());
			$this->TAL->setTranslator($translator);
			$this->TAL->setForceReparse(LBoxConfigSystem::getInstance()->getParamByPath("output/tal/PHPTAL_FORCE_REPARSE"));
			$this->TAL->setPhpCodeDestination($phptalPhpCodeDestination);
			$this->TAL->SELF = $this;
			return $this->TAL;
		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter nazvu souboru se sablonou
	 * @return string
	 */
	protected function getTemplateFileName() {
		$type	= get_class($this);
		$OneRecordType = eval("return $type::TEMPLATE_FILENAME;");																			 //
		if (strlen(eval("return $type::TEMPLATE_FILENAME;")) < 1) {
			throw new LBoxExceptionMetanodes("$type::TEMPLATE_FILENAME: ". LBoxExceptionMetanodes::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionComponent::CODE_BAD_INSTANCE_VAR);
		}
		return eval("return $type::TEMPLATE_FILENAME;");
	}
}
?>