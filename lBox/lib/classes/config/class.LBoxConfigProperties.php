<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxConfigProperties extends LBoxConfig
{
	protected static $instance;
	protected $configName 			= "properties";
	protected $classNameIterator	= "LBoxIteratorProperties";
	protected $classNameItem		= "LBoxConfigItemProperty";
	protected $nodeName				= "property";
	
	public function resetInstance() {
		try {
			self::$instance	= NULL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * @return LBoxConfigProperties
	 * @throws Exception
	 */
	public static function getInstance() {
		$className 	= __CLASS__;
		try {
			if (!self::$instance instanceof $className) {
				self::$instance = new $className;
			}
			return self::$instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizeno o kontrolu vzhledem k multilang
	 */
	public function getDOM() {
		try {
			if ($this->dom instanceof DOMDocument) {
				return $this->dom;
			}
			$configNameBase	= $this->configName;
			try {
				$this->configName .= ".". LBoxFront::getDisplayLanguage();
				return parent::getDOM();
			}
			catch(Exception $e) {
				try {
					$this->configName	= $configNameBase;
					return parent::getDOM();
				}
				catch (Exception $e) {
					if ($e->getCode() == LBoxExceptionConfig::CODE_TYPE_NOT_FOUND) {
						die($this->configName .".xml not found due to langdomain did not recognized!");
					}
				}
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizeno o nastaveni povinnych hodnot
	 * @param string $name
	 * @param string $value
	 */
	public function getCreateItem($name = "", $value = "") {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_PARAM_STRING_NOTNULL, LBoxExceptionConfig::CODE_BAD_PARAM);
			}
			try {
				if (LBoxConfigManagerProperties::getInstance()->getPropertyByName($name)) {
					throw new LBoxExceptionConfig("This property already exists!");
				}
			}
			catch (Exception $e) {
				switch ($e->getCode()) {
					case LBoxExceptionProperty::CODE_PROPERTY_NOT_FOUND:
						break;
					default:
						throw $e;
				}
			}
			$instance	= parent::getCreateItem();
			$instance->name	= $name;
			$instance->getNode()->nodeValue	= $value;
			return $instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>