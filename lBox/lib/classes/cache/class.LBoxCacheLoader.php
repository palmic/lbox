<?php

/**
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox
 * @version 1.0

 * @date 2008-02-13
 */
class LBoxCacheLoader extends LBoxCache
{
	protected $fileName	= "loader.cache";

	protected static $instance;

	public function __destruct() {
		try {
			$this->saveCachedData();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return LBoxCacheLoader
	 * @throws LBoxExceptionCache
	 */
	public static function getInstance ($fileName	= "") {
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