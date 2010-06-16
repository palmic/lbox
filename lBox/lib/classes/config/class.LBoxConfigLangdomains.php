<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2009-05-18
*/
class LBoxConfigLangdomains extends LBoxConfig
{
	protected static $instance;
	protected $configName 			= "langdomains";
	protected $classNameIterator	= "LBoxIteratorLangdomains";
	protected $classNameItem		= "LBoxConfigItemLangdomain";
	protected $classNameManager		= "LBoxConfigManagerLangdomains";
	protected $nodeName				= "domain";
	
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