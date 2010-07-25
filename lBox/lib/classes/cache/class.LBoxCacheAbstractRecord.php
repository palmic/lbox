<?php

/**
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox
 * @version 1.0
 * @date 2008-10-02
 */
class LBoxCacheAbstractRecord extends LBoxCache2
{
	protected static $instances = array();
	
	/**
	 * cache var
	 * @var string
	 */
	protected $recordType	= "";

	protected function getDir () {
		try {
			return LBoxConfigSystem::getInstance()->getParamByPath("records/cache/path") . SLASH . $this->recordType;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @param string $recordType
	 * @param string $id
	 * @param string $group
	 * @return LBoxCacheAbstractRecord
	 * @throws LBoxExceptionCache
	 */
	public static function getInstance ($recordType	= "", $id	= "", $group = "") {
		$className 	= __CLASS__;
		try {
			$rk	= md5($recordType);$gk	= md5($group);$gid	= md5($id);
			if (array_key_exists($rk, self::$instances) && array_key_exists($gk, self::$instances[$rk]) && array_key_exists($gid, self::$instances[$rk][$gk])) {
				if (self::$instances[$rk][$gk][$gid] instanceof $className) {
					return self::$instances[$rk][$gk][$gid];
				}
			}
			return self::$instances[$rk][$gk][$gid] = new $className($recordType, $id, $group);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * pretizeno o record type
	 * @param string $id
	 * @param string $id
	 * @param string $group
	 */
	protected function __construct($recordType = "", $id = "", $group = "") {
		if (strlen($recordType) < 1) {
			throw new LBoxExceptionCache("\$recordType: ". LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
		}
		$this->recordType	=	 $recordType;
		$this->id			=	 $id;
		$this->group		=	 $group;
	}

	/**
	 * pretizeno o vyjmuti nekterych records
	 */
	public function reset () {
		try {
			switch ($this->recordType) {
				case "acces":return;break;
			}
			parent::reset();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * pretizeno o vyjmuti nekterych records
	 * @param bool $groupOnly
	 * @param string $mode
	 */
	public function clean($groupOnly = true, $mode = "ingroup") {
		try {
			switch ($this->recordType) {
				case "acces":return;break;
			}
			parent::clean($groupOnly, $mode);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>