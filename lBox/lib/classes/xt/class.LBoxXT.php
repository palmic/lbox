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
	 * cache na zaznam login usera
	 * @var XTUsersRecord
	 */
	protected static $xtUserRecord;
	
	/**
	 * cache na zaznam login role usera
	 * @var XTRolesRecord
	 */
	protected static $xtRoleRecord;
	
	/**
	 * @var LBoxXT
	 */
	protected static $instance;
	
	/**
	 * Try to log user into system with given nick and password
	 * @param string $nick
	 * @param string $password
	 * @param bool $remember
	 * @throws LBoxExceptionXT
	 */
	public static function login($nick = "", $password = "", $remember = false) {
		try {
			if (strlen($nick) < 1) {
				throw new LBoxExceptionXT("\$nick: ". LBoxExceptionXT::MSG_PARAM_STRING_NOTNULL, LBoxExceptionXT::CODE_BAD_PARAM);
			}
			if (strlen($password) < 1) {
				throw new LBoxExceptionXT("\$password: ". LBoxExceptionXT::MSG_PARAM_STRING_NOTNULL, LBoxExceptionXT::CODE_BAD_PARAM);
			}
			$records	= new XTUsersRecords(array("nick" => $nick, "password" => $password));
			if ($records->count() < 1) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_LOGIN_INVALID, LBoxExceptionXT::CODE_LOGIN_INVALID);
			}
			if ($records->current()->confirmed != 1) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_USER_NOT_CONFIRMED, LBoxExceptionXT::CODE_USER_NOT_CONFIRMED);
			}
			// zjistime si jestli nejde o re-login a pokud ano, nastavime cookie-remember ano/ne podle predchoziho loginu
			if (self::isLogged()) $remember = (strlen($_COOKIE[self::COOKIE_NAME_LOGIN]) > 0);
			// logout - pro pripad re-loginu
			self::logout();
			unset($_SESSION["lbox"][self::SESSION_ARRAY_NAME]["logout"]);
			self::$xtUserRecord	= $records->current();
			self::$xtRoleRecord	= self::$xtUserRecord->getRole();
			$_SESSION["lbox"][self::SESSION_ARRAY_NAME]["signon"]	= true;
			$_SESSION["lbox"][self::SESSION_ARRAY_NAME]["userId"]	= self::$xtUserRecord->id;
			$_SESSION["lbox"][self::SESSION_ARRAY_NAME]["roleId"]	= self::$xtRoleRecord->id;
			
			// remember pres cookie
			if ($remember) {
				$rememberDays	= self::getCookiePersistenceDays();
				@setcookie(self::COOKIE_NAME_LOGIN, "$nick:$password", time() + $rememberDays * 24*60*60, "/");
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Logout user
	 * @throws LBoxExceptionXT
	 */
	public static function logout() {
		try {
			setcookie(self::COOKIE_NAME_LOGIN, false, time()-3600, "/");
			unset($_SESSION["lbox"][self::SESSION_ARRAY_NAME]);
			$_SESSION["lbox"][self::SESSION_ARRAY_NAME]["logout"] = 1;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * checks if user is signed on
	 * @return bool
	 */
	public static function isLogged() {
		try {
			if (array_key_exists("lbox", $_SESSION)) {
				if ($_SESSION["lbox"][self::SESSION_ARRAY_NAME]["logout"] == 1) {
					return false;
				}
				if ((bool)$_SESSION["lbox"][self::SESSION_ARRAY_NAME]["signon"]) {
					return true;
				}
			}
			// prihlaseni z cookie
			if (array_key_exists(self::COOKIE_NAME_LOGIN, $_COOKIE))
			if (strlen($_COOKIE[self::COOKIE_NAME_LOGIN]) > 0) {
				$login 			= explode(":", $_COOKIE[self::COOKIE_NAME_LOGIN]);
				// logout pro obnoveni persistence cookie
				self::logout();
				self::login($login[0], $login[1], true);
			}
			if (array_key_exists("lbox", $_SESSION)) {
				return (bool)$_SESSION["lbox"][self::SESSION_ARRAY_NAME]["signon"];
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
	 * @return bool
	 */
	public static function isLoggedAdmin() {
		try {
			if (!self::isLogged()) {
				return false;
				// throw new LBoxExceptionXT(LBoxExceptionXT::MSG_NOT_LOGGED, LBoxExceptionXT::CODE_NOT_LOGGED);
			}
			switch (trim(self::getUserXTRoleRecord()->name)) {
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
	 * @return bool
	 */
	public static function isLoggedSuperAdmin() {
		try {
			if (!self::isLogged()) {
				return false;
				// throw new LBoxExceptionXT(LBoxExceptionXT::MSG_NOT_LOGGED, LBoxExceptionXT::CODE_NOT_LOGGED);
			}
			switch (self::getUserXTRoleRecord()->name) {
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
	 * @return XTUsersRecord
	 * @throws LBoxExceptionXT
	 */
	public static function getUserXTRecord() {
		try {			
			if (!self::isLogged()) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_NOT_LOGGED, LBoxExceptionXT::CODE_NOT_LOGGED);
			}
			if (self::$xtUserRecord instanceof XTUsersRecord) {
				return self::$xtUserRecord;
			}
			return self::$xtUserRecord = new XTUsersRecord($_SESSION["lbox"][self::SESSION_ARRAY_NAME]["userId"]);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vrati XT roli uzivatele pokud je jiz zalogovan
	 * @return XTRolesRecord
	 * @throws LBoxExceptionXT
	 */
	public static function getUserXTRoleRecord() {
		try {			
			if (!self::isLogged()) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_NOT_LOGGED, LBoxExceptionXT::CODE_NOT_LOGGED);
			}
			if (self::$xtRoleRecord instanceof XTRolesRecord) {
				return self::$xtRoleRecord;
			}
			return self::$xtRoleRecord = new XTRolesRecord($_SESSION["lbox"][self::SESSION_ARRAY_NAME]["roleId"]);
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
			$records	= new XTRolesRecords(array("name" => $name));
			if ($records->count() < 1) {
				throw new LBoxExceptionXT("'$name' ". LBoxExceptionXT::MSG_ROLE_NOT_EXISTS, LBoxExceptionXT::CODE_ROLE_NOT_EXISTS);
			}
			return $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na vsechny XT roles v systemu, krome roli, ktere jsou vyhrazene jako superrole
	 * @param bool $superAdmin - with superadmin
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