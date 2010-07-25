<?php
/**
 * Page classes protocol
* @author Michal Palma <michal.palma@gmail.com>
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