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
}
?>