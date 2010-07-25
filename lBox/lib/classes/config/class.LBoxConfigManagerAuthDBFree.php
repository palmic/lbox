<?php
/**
 * Page classes protocol
* @author Michal Palma <michal.palma@gmail.com>
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
	
	protected $classNameConfig 				= "LBoxConfigAuthDBFree";

	/**
	 * cache var
	 * @var array
	 */
	protected static $loginsByPasswords		= array();
	
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
	 * getter na loginy podle hesla, pokud takovy existuje (heslo sam prevadi do md5)
	 * @param string $password
	 * @return array
	 * @throws LBoxException
	 */
	public function getLoginsByPassword($password = "") {
		try {
			$password	= md5($password);
			if (array_key_exists($password, self::$loginsByPasswords) && count(self::$loginsByPasswords[$password]) > 0) {
				return self::$loginsByPasswords[$password];
			}
			foreach ($this->getConfigInstance()->getRootIterator() as $node) {
				if ($node->password == $password) {
					$loginsByPasswords[$password][] = $node;
				}
			}
			return $loginsByPasswords[$password];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na jmena loginu podle jmena
	 * @param string $name
	 * @return LBoxConfigItemAuthDBFree
	 * @throws LBoxException
	 */
	public function getLoginByName($name = "") {
		try {
			return $this->getConfigInstance()->getInstance()->getNodeById($name);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>