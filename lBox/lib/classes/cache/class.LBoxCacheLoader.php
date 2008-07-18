<?php

/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0

 * @date 2008-02-13
 */
class LBoxCacheLoader extends LBoxCache
{
	protected $fileName	= "loader.cache";

	/**
	 * @return LBoxCacheLoader
	 * @throws LBoxExceptionCache
	 */
	public static function getInstance () {
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