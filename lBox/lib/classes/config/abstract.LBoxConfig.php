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
	 * vraci objekt s config DOMem
	 * @return DOMDocument
	 * @throws LBoxExceptionConfig
	 */
	protected function getDOM() {
		if (strlen($this->configName) < 1) {
			throw new  LBoxExceptionConfig(LBoxExceptionConfig::MSG_CFG_FILE_NOT_DEFINED, LBoxExceptionConfig::CODE_CFG_FILE_NOT_DEFINED);
		}		
		try {
			if (strlen($path = LBoxLoaderConfig::getInstance()->getPathOf($this->configName)) < 1) {
				throw new  LBoxExceptionConfig("'". $this->configName ."' ". LBoxExceptionConfig::MSG_TYPE_NOT_FOUND, LBoxExceptionConfig::CODE_TYPE_NOT_FOUND);
			}
			$domDocument = new DOMDocument;
			if (!$domDocument->load($path)) {
				throw new  LBoxExceptionConfig("'$path'". LBoxExceptionConfig::MSG_INVALID_PATH, LBoxExceptionConfig::CODE_INVALID_PATH);
			}
			return $domDocument;
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
			$instance->setParent($this->getDOM()->documentElement);
			return $this->rootIterator = $instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function __construct() {
	}

	abstract public static function getInstance();
}
?>