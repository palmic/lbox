<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2007-12-08
*/
abstract class LBoxConfig extends LBox
{
	/**
	 * nazev konfiguracniho souboru bez pripony - definovat v podtride!
	 * @var string 
	 */
	protected $configName;

	/**
	 * typ vyuzivaneho iteratoru - definovat v podtride!
	 * @var string
	 */
	protected $classNameIterator;
	
	/**
	 * Trida jednotlivych items - definovat v podtride!
	 * @var string
	 */
	protected $classNameItem;

	/**
	 * manager trida
	 * @var string
	 */
	protected $classNameManager;
	
	/**
	 * item nodeName - musi byt definano v podtride!
	 * @var string
	 */
	protected $nodeName;

	/**
	 * cache var
	 * @var DOMXPath
	 */
	protected static $xPath;

	/**
	 * cache var
	 * @var LBoxIteratorConfig
	 */
	protected $rootIterator;
	
	/**
	 * cache var
	 * @var DOMDocument
	 */
	protected $dom;
	
	/**
	 * vraci objekt s config DOMem
	 * @return DOMDocument
	 * @throws LBoxExceptionConfig
	 */
	public function getDOM() {
		if ($this->dom instanceof DOMDocument) {
			return $this->dom;
		}
		if (strlen($this->configName) < 1) {
			throw new  LBoxExceptionConfig(LBoxExceptionConfig::MSG_CFG_FILE_NOT_DEFINED, LBoxExceptionConfig::CODE_CFG_FILE_NOT_DEFINED);
		}		
		try {
			if (strlen($path = LBoxLoaderConfig::getInstance()->getPathOf($this->configName)) < 1) {
				throw new  LBoxExceptionConfig("'". $this->configName ."' ". LBoxExceptionConfig::MSG_TYPE_NOT_FOUND, LBoxExceptionConfig::CODE_TYPE_NOT_FOUND);
			}
			$this->dom = new DOMDocument;
			$this->dom->formatOutput		= true;
			$this->dom->preserveWhiteSpace	= false;
			if (!$this->dom->load($path)) {
				throw new  LBoxExceptionConfig("'$path'". LBoxExceptionConfig::MSG_INVALID_PATH, LBoxExceptionConfig::CODE_INVALID_PATH);
			}
			return $this->dom;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na xPath
	 * @return DOMXPath
	 */
	protected function getXPath() {
		try {
			if (self::$xPath instanceof DOMXPath) {
				return self::$xPath;
			}
			return self::$xPath = new DOMXPath($this->getDOM());
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci iterator prvni urovne configu
	 * @return LBoxIteratorConfig
	 * @throws Exception
	 */
	public function getRootIterator() {
		try {
			if ($this->rootIterator instanceof LBoxIteratorConfig) {
				return $this->rootIterator;
			}
			if (strlen($className = $this->classNameIterator) < 1) {
				throw new  LBoxExceptionConfig(LBoxExceptionConfig::MSG_ABSTRACT_CLASSNAME_NOT_DEFINED, LBoxExceptionConfig::CODE_ABSTRACT_CLASSNAME_NOT_DEFINED);
			}
			$instance			= eval("return new $className;");
			if (!$instance instanceof LBoxIteratorConfig) {
				throw new  LBoxExceptionConfig(LBoxExceptionConfig::MSG_CLASS_NOT_ITERATOR_CONFIG, LBoxExceptionConfig::CODE_CLASS_NOT_ITERATOR_CONFIG);
			}
			$instance->setConfig($this);
			$instance->setParent($this->getDOM()->documentElement);
			return $this->rootIterator = $instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vytvori a vrati novou item automaticky vlozeny nakonec struktury
	 * @return LBoxConfigItem
	 */
	public function getCreateItem() {
		try {
			if (strlen($className = $this->classNameItem) < 1) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_ABSTRACT_CLASSNAME_NOT_DEFINED, LBoxExceptionConfig::CODE_ABSTRACT_CLASSNAME_NOT_DEFINED);
			}
			if (strlen($this->nodeName) < 1) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_ABSTRACT_NODENAME_NOT_DEFINED, LBoxExceptionConfig::CODE_ABSTRACT_NODENAME_NOT_DEFINED);
			}
			$instance	= eval("return new $className;");
			if (!$instance instanceof LBoxConfigItem) {
				throw new LBoxExceptionConfig("$className class ". LBoxExceptionConfig::MSG_CLASS_NOT_CONFIG_ITEM, LBoxExceptionConfig::CODE_CLASS_NOT_CONFIG_ITEM);
			}
			if (!$node = $this->dom->createElement($this->nodeName)) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_NODE_CANNOT_CREATE, LBoxExceptionConfig::CODE_NODE_CANNOT_CREATE);
			}
			$this->dom->documentElement->appendChild($node);
			$instance->setNode($node);
			$instance->setConfig($this);
			return $instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * uklada dokument zpet do filu
	 */
	public function store() {
		try {
			if (strlen($path = LBoxLoaderConfig::getInstance()->getPathOf($this->configName)) < 1) {
				throw new  LBoxExceptionConfig("'". $this->configName ."' ". LBoxExceptionConfig::MSG_TYPE_NOT_FOUND, LBoxExceptionConfig::CODE_TYPE_NOT_FOUND);
			}
			if (strlen($classNameManager = $this->classNameManager) < 1) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_ABSTRACT_CLASSNAME_NOT_DEFINED, LBoxExceptionConfig::CODE_ABSTRACT_CLASSNAME_NOT_DEFINED);
			}
			if ($this->getDOM()->save($path) === FALSE) {
				throw new LBoxExceptionConfig("$path: ". LBoxExceptionConfig::MSG_DOCUMENT_CANNOT_SAVE, LBoxExceptionConfig::CODE_DOCUMENT_CANNOT_SAVE);
			}
			
			$this->dom			= NULL;
			$this->rootIterator	= NULL;
			self::$xPath		= NULL;
			$classNameMe		= get_class($this);
			eval("return $classNameManager::resetInstance();");
			eval("return $classNameMe::resetInstance();");
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function __construct() {
	}

	abstract public static function getInstance();

	abstract public function resetInstance();
}
?>