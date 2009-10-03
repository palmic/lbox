<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2009-10-03
*/
class LBoxXTDBFree extends LBox
{
	const SESSION_ARRAY_NAME		= "xt-db-free";
	const COOKIE_NAME_LOGIN			= "lbox-xt-db-free-login";
	
	/**
	 * cache vars
	 */
	protected static $isLogged = array();
	
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
			if (LBoxConfigManagerAuthDBFree::getInstance()->getNameByPassword($password) != $nick) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_LOGIN_INVALID, LBoxExceptionXT::CODE_LOGIN_INVALID);
			}
			// zjistime si jestli nejde o re-login a pokud ano, nastavime cookie-remember ano/ne podle predchoziho loginu
			if (self::isLogged($loginGroup)) $remember = (strlen($_COOKIE[self::COOKIE_NAME_LOGIN ."-". $loginGroup]) > 0);
			// logout - pro pripad re-loginu
			self::logout();
			unset($_SESSION["lbox"][self::SESSION_ARRAY_NAME][0]["logout"]);
			unset($_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["logout"]);
			$_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["signon"]	= true;
			
			// remember pres cookie
			if ($remember) {
				$rememberDays	= self::getCookiePersistenceDays();
				@LBoxFront::setCookie(self::COOKIE_NAME_LOGIN ."-". $loginGroup, "$nick:$password", time() + $rememberDays * 24*60*60, "/");
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
			LBoxFront::setCookie(self::COOKIE_NAME_LOGIN ."-". $loginGroup, false, time()-3600, "/");
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
			if (array_key_exists($loginGroup, self::$isLogged)) {
				return self::$isLogged[$loginGroup];
			}
			if (!is_array($_SESSION["lbox"][self::SESSION_ARRAY_NAME])) {
				return self::$isLogged[$loginGroup] = false;
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
					return self::$isLogged[$loginGroup] = false;
				}
				else if ($_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["logout"] == 1) {
					return self::$isLogged[$loginGroup] = false;
				}
				if ((bool)$_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["signon"]) {
					return self::$isLogged[$loginGroup] = true;
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
				return self::$isLogged[$loginGroup] = (bool)$_SESSION["lbox"][self::SESSION_ARRAY_NAME][$loginGroup]["signon"];
			}
			else {
				return self::$isLogged[$loginGroup] = false;
			}
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