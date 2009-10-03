<?php
/**
 * Page classes protocol
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2009-10-03
*/
class LBoxConfigManagerAuthDBFree extends LBoxConfigManager
{
	/**
	 * @var LBoxConfigManagerComponents
	 */
	protected static $instance;
	
	protected $classNameConfig 		= "LBoxConfigAuthDBFree";

	/**
	 * @return LBoxConfigManagerAuthDBFree
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
	 * getter na login podle hesla, pokud takovy existuje (heslo sam prevadi do md5)
	 * @param string $password
	 * @return LBoxConfigItemAuthDBFree
	 * @throws LBoxException
	 */
	public function getLoginByPassword($password = "") {
		try {
			foreach ($this->getConfigInstance()->getRootIterator() as $node) {
				if ($node->password == md5($password)) {
					return $node;
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na name loginu podle hesla
	 * @param string $password
	 * @return string
	 * @throws LBoxException
	 */
	public function getNameByPassword($password = "") {
		try {
			if ($node = $this->getLoginByPassword($password)) {
				return $node->name;
			}
			else {
				return NULL;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>