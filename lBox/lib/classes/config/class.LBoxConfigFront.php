<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @date 2011-02-03
*/
class LBoxConfigFront extends LBoxConfig
{
	protected static $instance;
	protected $configName 			= "front";
	protected $classNameIterator            = "LBoxIteratorConfigFront";
	protected $classNameItem		= "LBoxConfigItemFront";
	protected $classNameManager		= "LBoxConfigManagerFront";
	protected $nodeName                     = "option";
	
	public function resetInstance() {
		try {
			self::$instance	= NULL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * @return LBoxConfigLangdomains
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