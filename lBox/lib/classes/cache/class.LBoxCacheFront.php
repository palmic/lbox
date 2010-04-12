<?php

/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @date 2010-04-08
 */
class LBoxCacheFront extends LBoxCache2
{
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
	 * @param $url
	 * @param $xtUserID
	 * @return LBoxCacheFront
	 * @throws LBoxExceptionCache
	 */
	public static function getInstance($url = "", $xtUserID = "") {
		try {
			if (strlen($url) < 1) 		{ $url 		= self::getCacheGroup(); }
			if (strlen($xtUserID) < 1) 	{ $xtUserID = self::getCacheID(); }
			$id		= $xtUserID;
			$group	= $url;
			self::$lifeTime	= LBoxConfigSystem::getInstance()->getParamByPath("output/cache/expiration");

			$className 	= __CLASS__;
			try {
				if (self::$instance instanceof $className) {
					if (self::$instance->id	== $id && self::$instance->group == $group) {
						return self::$instance;
					}
				}
				return self::$instance = new $className($id, $group);
			}
			catch (Exception $e) {
				throw $e;
			}
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
			return LBoxXTProject::isLogged(XT_GROUP ? XT_GROUP : NULL) ? ("xtu". LBoxXTProject::getUserXTRecord(XT_GROUP ? XT_GROUP : NULL)->id) : "notlogged";			
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
			return LBOX_REQUEST_URL;			
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>