<?php

/**
 * Obecna Cache trida pro cachovani obecne cehokoli
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox
 * @version 1.0
 * @date 2010-04-08
 */
class LBoxCacheData extends LBoxCache2
{
	/**
	 * if true, cache will be writen on __destruct()
	 * @var bool
	 */
	private $autosave	= false;
	
	protected $hashedDirectoryLevel = 0;
	
	protected static $instances = array();

	public function setAutoSave($value = true) {
		try {
			$this->autosave	= $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function setLifeTime($time) {
		try {
			if (!is_int($time) || $time < 1) {
				throw new LBoxExceptionCache("\$time: ". LBoxExceptionCache::MSG_PARAM_INT, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			$this->lifeTime	= $time;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function getDir () {
		try {
			return $this->path;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function __destruct() {
		try {
			if ($this->autosave) {
				$this->saveCachedData();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * jiz nactena IDs pro kontroly
	 * @var array
	 */
	private static 	$ids	= array();
	
	/**
	 * cache dir path
	 * @var string
	 */
	private 		$path;
	
	/**
	 * @return LBoxCacheData
	 * @throws LBoxExceptionCache
	 */
	public static function getInstance ($id	= "", $group = "", $path) {
		$className 	= __CLASS__;
		try {
			$gk	= md5($group);$gid	= md5($id);
			if (array_key_exists($gk, self::$instances) && array_key_exists($gid, self::$instances[$gk])) {
				if (self::$instances[$gk][$gid] instanceof $className) {
					return self::$instances[$gk][$gid];
				}
			}
			if (array_key_exists($id, self::$ids)) {
				throw new LBoxExceptionCache("'$id': This ID is already used!");
			}
			self::$ids[$id]	= $id;
			return self::$instances[$gk][$gid] = new $className($id, $group, $path);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @param string $id
	 * @param string $group
	 */
	protected function __construct($id = "", $group = "", $path = LBOX_PATH_CACHE) {
		if (strlen($id) < 1) {
			throw new LBoxExceptionCache("\$id: ". LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
		}
		if (strlen($path) < 1) {
			throw new LBoxExceptionCache(LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
		}
		$this->id		= $id;
		$this->group	= $group;
		$this->path		= $path;
	}
}
?>