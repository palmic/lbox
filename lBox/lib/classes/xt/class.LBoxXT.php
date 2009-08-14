<?
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxXT extends LBox
{
	/**
	 * roles database records names
	 */
	const XT_ROLE_NAME_SUPERADMIN	= "superadmin";
	const XT_ROLE_NAME_ADMIN		= "admin";
	const XT_ROLE_NAME_USER			= "user";
	
	const SESSION_ARRAY_NAME		= "xt";
	const COOKIE_NAME_LOGIN			= "lbox-xt-login";
	
	/**
	 * cache na zaznamy loginu uzivatelu podle logingroups
	 * @var array
	 */
	protected static $xtUserRecords = array();

	/**
	 * cache na zaznamy login roli uzivatelu podle logingroups indexovano podel user->id
	 * @var array
	 */
	protected static $xtRoleRecords = array();

	/**
	 * cache roli podle jejich nazvu
	 * @var array
	 */
	protected static $xtRolesRecordsByNames = array();

	/**
	 * @var LBoxXT
	 */
	protected static $instance;

	/**
	 * Try to log user into system with given nick and password
	 * @param string $nick
	 * @param string $password
	 * @param bool $remember
	 * @param int $loginGroup - you can define more logins for instance for more web xt sections
	 * @throws LBoxExceptionXT
	 */
	public static function login($nick = "", $password = "", $remember = false, $loginGroup = 1) {
		try {
			if ($loginGroup < 1) {
				if (strlen(LBoxFront::getPage()->xt) > 0) {
					$loginGroup = LBoxFront::getPage()->xt;
				}
				else {
					$loginGroup = 1;
				}
			}
			if (strlen($nick) < 1) {
				throw new LBoxExceptionXT("\$nick: ". LBoxExceptionXT::MSG_PARAM_STRING_NOTNULL, LBoxExceptionXT::CODE_BAD_PARAM);
			}
			if (strlen($password) < 1) {
				throw new LBoxExceptionXT("\$password: ". LBoxExceptionXT::MSG_PARAM_STRING_NOTNULL, LBoxExceptionXT::CODE_BAD_PARAM);
			}
			if (!is_int($loginGroup) || $loginGroup < 1) {
				throw new LBoxExceptionXT("\$loginGroup: ". LBoxExceptionXT::MSG_PARAM_INT_NOTNULL, LBoxExceptionXT::CODE_BAD_PARAM);
			}
			$records	= new XTUsersRecords(array("nick" => $nick, "password" => $password));
			if ($records->count() < 1) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_LOGIN_INVALID, LBoxExceptionXT::CODE_LOGIN_INVALID);
			}
			if ($records->current()->confirmed != 1) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_USER_NOT_CONFIRMED, LBoxExceptionXT::CODE_USER_NOT_CONFIRMED);
			}
			// zjistime si jestli nejde o re-login a pokud ano, nastavime cookie-remember ano/ne podle predchoziho loginu
			if (self::isLogged($loginGroup)) $remember = (strlen($_COOKIE[self::COOKIE_NAME_LOGIN ."-". $loginGroup]) > 0);
			// logout - pro pripad re-loginu
			self::logout();
			unset($_SESSION["lbox"][self::SESSION_ARRAY_NAME][0]["logout"]);
			unset($_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["logout"]);
			self::$xtUserRecords[$loginGroup]							= $records->current();
			self::$xtRoleRecords[self::$xtUserRecords[$loginGroup]->id]	= self::$xtUserRecords[$loginGroup]->getRole();
			$_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["signon"]	= true;
			$_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["userId"]	= self::$xtUserRecords[$loginGroup]->id;
			$_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["roleId"]	= self::$xtRoleRecords[self::$xtUserRecords[$loginGroup]->id]->id;
			
			// remember pres cookie
			if ($remember) {
				$rememberDays	= self::getCookiePersistenceDays();
				@setcookie(self::COOKIE_NAME_LOGIN ."-". $loginGroup, "$nick:$password", time() + $rememberDays * 24*60*60, "/");
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Logout user
	 * @param int $loginGroup - pokud < 1, odloguje vsechny sekce 
	 * @throws LBoxExceptionXT
	 */
	public static function logout($loginGroup = 0) {
		try {
			if ($loginGroup < 1) {
				if (strlen(LBoxFront::getPage()->xt) > 0) {
					$loginGroup = LBoxFront::getPage()->xt;
				}
				else {
					$loginGroup = 1;
				}
			}
			setcookie(self::COOKIE_NAME_LOGIN ."-". $loginGroup, false, time()-3600, "/");
			unset($_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]);
			if ($loginGroup < 1) {
				unset($_SESSION["lbox"][self::SESSION_ARRAY_NAME]);
			}
			$_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["logout"] = 1;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * checks if user is signed on
	 * @param int $loginGroup
	 * @return bool
	 */
	public static function isLogged($loginGroup = 0) {
		try {
			if (!is_array($_SESSION["lbox"][self::SESSION_ARRAY_NAME])) {
				return false;
			}
			if ($loginGroup === 0) {
				if (strlen(LBoxFront::getPage()->xt) > 0) {
					$loginGroup = LBoxFront::getPage()->xt;
				}
				else {
					$loginGroup = 1;
				}
			}
			if (array_key_exists("lbox", (array)$_SESSION)) {
				if ($_SESSION["lbox"][self::SESSION_ARRAY_NAME][0]["logout"] == 1) {
					return false;
				}
				else if ($_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["logout"] == 1) {
					return false;
				}
				if ((bool)$_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["signon"]) {
					return true;
				}
			}
			// prihlaseni z cookie
			if (array_key_exists(self::COOKIE_NAME_LOGIN ."-". $loginGroup, $_COOKIE))
			if (strlen($_COOKIE[self::COOKIE_NAME_LOGIN ."-". $loginGroup]) > 0) {
				$login 			= explode(":", $_COOKIE[self::COOKIE_NAME_LOGIN ."-". $loginGroup]);
				$group			= is_numeric($login[2]) ? (int)$login[2] : 1;
				// logout pro obnoveni persistence cookie
				self::logout();
				self::login($login[0], $login[1], true, $group);
			}
			if (array_key_exists("lbox", (array)$_SESSION)) {
				return (bool)$_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["signon"];
			}
			else {
				return false;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * checks if user is signed on
	 * @param int $loginGroup
	 * @return bool
	 */
	public static function isLoggedAdmin($loginGroup = 0) {
		try {
			if ($loginGroup < 1) {
				if (strlen(LBoxFront::getPage()->xt) > 0) {
					$loginGroup = LBoxFront::getPage()->xt;
				}
				else {
					$loginGroup = 1;
				}
			}
			if (!self::isLogged($loginGroup)) {
				return false;
				// throw new LBoxExceptionXT(LBoxExceptionXT::MSG_NOT_LOGGED, LBoxExceptionXT::CODE_NOT_LOGGED);
			}
			switch (trim(self::getUserXTRoleRecord($loginGroup)->name)) {
				case self::XT_ROLE_NAME_SUPERADMIN:
				case self::XT_ROLE_NAME_ADMIN:
					return true;
				default:
					return false;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * checks if user is signed on
	 * @param int $loginGroup
	 * @return bool
	 */
	public static function isLoggedSuperAdmin($loginGroup = 0) {
		try {
			if ($loginGroup < 1) {
				if (strlen(LBoxFront::getPage()->xt) > 0) {
					$loginGroup = LBoxFront::getPage()->xt;
				}
				else {
					$loginGroup = 1;
				}
			}
			if (!self::isLogged($loginGroup)) {
				return false;
				// throw new LBoxExceptionXT(LBoxExceptionXT::MSG_NOT_LOGGED, LBoxExceptionXT::CODE_NOT_LOGGED);
			}
			switch (self::getUserXTRoleRecord($loginGroup)->name) {
				case self::XT_ROLE_NAME_SUPERADMIN:
					return true;
				default:
					return false;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vrati record XT uzivatele pokud je jiz zalogovan
	 * @param int $loginGroup
	 * @return XTUsersRecord
	 * @throws LBoxExceptionXT
	 */
	public static function getUserXTRecord($loginGroup = 0) {
		try {			
			if ($loginGroup < 1) {
				if (strlen(LBoxFront::getPage()->xt) > 0) {
					$loginGroup = LBoxFront::getPage()->xt;
				}
				else {
					$loginGroup = 1;
				}
			}
			if (!self::isLogged($loginGroup)) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_NOT_LOGGED, LBoxExceptionXT::CODE_NOT_LOGGED);
			}
			if (self::$xtUserRecords[$loginGroup] instanceof XTUsersRecord) {
				return self::$xtUserRecords[$loginGroup];
			}
			return self::$xtUserRecords[$loginGroup] = new XTUsersRecord($_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["userId"]);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vrati XT roli uzivatele pokud je jiz zalogovan
	 * @param int $loginGroup
	 * @return XTRolesRecord
	 * @throws LBoxExceptionXT
	 */
	public static function getUserXTRoleRecord($loginGroup = 0) {
		try {			
			if ($loginGroup < 1) {
				if (strlen(LBoxFront::getPage()->xt) > 0) {
					$loginGroup = LBoxFront::getPage()->xt;
				}
				else {
					$loginGroup = 1;
				}
			}
			if (!self::isLogged($loginGroup)) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_NOT_LOGGED, LBoxExceptionXT::CODE_NOT_LOGGED);
			}
			if (self::$xtRoleRecords[self::getUserXTRecord($loginGroup)->id] instanceof XTRolesRecord) {
				return self::$xtRoleRecords[self::getUserXTRecord($loginGroup)->id];
			}
			return self::$xtRoleRecords[self::getUserXTRecord($loginGroup)->id] = self::getUserXTRecord($loginGroup)->getRole();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na role podle jejich jména
	 * @param string $name
	 * @return XTRolesRecord
	 * @throws Exception
	 */
	public static function getRoleRecordByName($name = "") {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_PARAM_STRING_NOTNULL, LBoxExceptionXT::CODE_BAD_PARAM);
			}
			if (self::$xtRolesRecordsByNames[$name] instanceof XTRolesRecord) {
				return self::$xtRolesRecordsByNames[$name];
			}
			$records	= new XTRolesRecords(array("name" => $name));
			if ($records->count() < 1) {
				throw new LBoxExceptionXT("'$name' ". LBoxExceptionXT::MSG_ROLE_NOT_EXISTS, LBoxExceptionXT::CODE_ROLE_NOT_EXISTS);
			}
			return self::$xtRolesRecordsByNames[$name] = $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na vsechny XT roles v systemu, krome roli, ktere jsou vyhrazene jako superrole
	 * @param bool $superAdmin - with superadmin roles
	 * @return XTRolesRecords
	 * @throws Exception
	 */
	public static function getXTRoles($superAdmin = false) {
		try {
			if ($superAdmin) {
				$where = NULL;
			}
			else {
				//$where = $superAdmin ? NULL : "name != '". self::XT_ROLE_NAME_SUPERADMIN ."'";
				$where	= new QueryBuilderWhere();
				$where	->addConditionColumn("name", self::XT_ROLE_NAME_SUPERADMIN, -3);
				$where	->addConditionColumn($pidColName, "<<NULL>>", 0, 1);
			}
			return new XTRolesRecords(false, array("id" => 0), false, $where);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * @return LBoxXT
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
	 * Vraci pocet dni, po ktere ma byt remember cookie aktivni
	 * @return int
	 * @throws Exception
	 */
	protected static function getCookiePersistenceDays() {
		try {
			return (int)LBoxConfigSystem::getInstance()->getParamByPath("xt/remember_cookie_days");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function __construct() {
	}	
}
?>