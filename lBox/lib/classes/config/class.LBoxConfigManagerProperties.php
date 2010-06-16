<?php
/**
 * Structure config manager
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxConfigManagerProperties extends LBoxConfigManager
{
	/**
	 * @var LBoxConfigManagerProperties
	 */
	protected static $instance;
	
	protected $classNameConfig 		= "LBoxConfigProperties";

	/**
	 * @return LBoxConfigManagerProperties
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
	 * destroys singleton instance from cache
	 */
	public static function resetInstance() {
		try {
			self::$instance = NULL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter config vlastnosti by name
	 * @return LBoxConfigItemProperty
	 * @throws LBoxException
	 */
	public function getPropertyByName($name = "") {
		try{
			if (strlen($name) < 1) {
				throw new LBoxExceptionProperty(LBoxExceptionProperty::MSG_PARAM_STRING_NOTNULL, LBoxExceptionProperty::CODE_BAD_PARAM);
			}
			if (!$node = $this->getNodeByParam("name", $name)) {
				throw new LBoxExceptionProperty(LBoxExceptionProperty::MSG_PROPERTY_NOT_FOUND ." Tryed to find name = '$name'", LBoxExceptionProperty::CODE_PROPERTY_NOT_FOUND);
			}
			return $node;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * shortcut na obsah property
	 * @param string $name
	 * @return string
	 * @throws LBoxException
	 */
	public static function getPropertyContentByName($name = "") {
		try {
			return self::getValueParsed(self::getInstance()->getPropertyByName($name)->getContent());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * alias k getPropertyContentByName
	 * @param string $name
	 * @return string
	 * @throws LBoxException
	 */
	public static function gpcn($name = "") {
		try {
			return self::getPropertyContentByName($name);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>