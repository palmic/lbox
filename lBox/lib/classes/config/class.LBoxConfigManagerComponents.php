<?php
/**
 * Page classes protocol
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxConfigManagerComponents extends LBoxConfigManager
{
	/**
	 * @var LBoxConfigManagerComponents
	 */
	protected static $instance;
	
	protected $classNameConfig 		= "LBoxConfigComponents";

	/**
	 * @return LBoxConfigManagerComponents
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
	 * getter configu konkretni komponenty
	 * @return LBoxConfigItemComponent
	 * @throws LBoxException
	 */
	public function getComponentById($id = "") {
		try {
			return $this->getConfigInstance()->getNodeById($id);
		}
		catch (Exception $e) {
			throw $e;
		}
	}	
}
?>