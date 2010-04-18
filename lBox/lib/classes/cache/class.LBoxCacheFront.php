<?php

/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @date 2010-04-08
 */
class LBoxCacheFront extends LBoxCache2
{
	/**
	 * pokud je nastaveno, trida naklada pouze s daty tohoto uzivatele bez ohledu na to, kdo je momentalne zalogovan
	 * @var int
	 */
	protected static $xTUserIDForce;
	
	protected static $instances = array();

	protected function getDir () {
		try {
			return LBoxConfigSystem::getInstance()->getParamByPath("output/cache/path");
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizeno o automaticke definovani cache ID a group (id = url, group = ID momentalne prihlaseneho uzivatele)
	 * @param string $url
	 * @param int $xtUserID
	 * @param bool $isCachedByXTUser
	 * @return LBoxCacheFront
	 * @throws LBoxExceptionCache
	 */
	public static function getInstance($url = "", $xtUserID = "", $isCachedByXTUser = true) {
		try {
			if (strlen($url) < 1) 		{ $url 		= self::getCacheGroup(); }
			if (strlen($xtUserID) < 1) 	{ $xtUserID = self::getCacheID(); }
			if ($isCachedByXTUser) {
				$id		= $xtUserID;
				$group	= $url;
			}
			else {
				$id		= $url;
				$group	= NULL;
			}
			$className 	= __CLASS__;
			try {
				$gk	= md5($group);$gid	= md5($id);
				if (array_key_exists($gk, self::$instances) && array_key_exists($gid, self::$instances[$gk])) {
					if (self::$instances[$gk][$gid] instanceof $className) {
						return self::$instances[$gk][$gid];
					}
				}
				return self::$instances[$gk][$gid] = new $className($id, $group);
			}
			catch (Exception $e) {
				throw $e;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function __construct($id = "", $group = "") {
		try {
			parent::__construct($id, $group);
			$this->lifeTime	= LBoxConfigSystem::getInstance()->getParamByPath("output/cache/expiration");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci cache id podle momentalne (ne)zalogovaneho uzivatele
	 * @return string
	 */
	public static function getCacheID() {
		try {
			switch (true) {
				case (is_numeric(self::$xTUserIDForce) && (self::$xTUserIDForce > 0)):
						return self::getCacheIDByXTUserID(self::$xTUserIDForce);
					break;
				case LBoxXTProject::isLogged():
						return self::getCacheIDByXTUserID(LBoxXTProject::getUserXTRecord()->id);
					break;
				default:
					return self::getCacheIDByXTUserID();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci cache id podle predaneho ID uzivatele
	 * @param int $xtUserID
	 * @return string
	 */
	public static function getCacheIDByXTUserID($xtUserID = "") {
		try {
			return $xtUserID ? ("xtu". $xtUserID) : "notlogged";			
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci cache group podle momentalni URL
	 * @return string
	 */
	public static function getCacheGroup() {
		try {
			$url	= (substr(LBOX_REQUEST_URL, -1) == "/") ? LBOX_REQUEST_URL : LBOX_REQUEST_URL . "/";
			$url	= str_replace("?/", "/", $url);
			$url	= str_replace("//", "/", $url);
			
			return $url;			
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci cache group podle momentalni URL
	 * @return int
	 */
	public static function setXTUserIDForce($id = 0) {
		try {
			if ($id instanceof XTUsersRecord) {
				$id	= $id->id;
			}
			if ((!is_numeric($id)) || ($id < 1)) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_PARAM_INT_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			self::$xTUserIDForce	= $id;			
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>