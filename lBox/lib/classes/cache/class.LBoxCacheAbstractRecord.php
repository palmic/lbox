<?php

/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @date 2008-10-02
 */
class LBoxCacheAbstractRecord extends LBoxCache2
{
	protected static $instances = array();

	protected function getDir () {
		try {
			return LBoxConfigSystem::getInstance()->getParamByPath("records/cache/path");
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @param string $id
	 * @param string $group
	 * @return LBoxCacheAbstractRecord
	 * @throws LBoxExceptionCache
	 */
	public static function getInstance ($id	= "", $group = "") {
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
	
	/**
	 * pretizeno bez kontroly id
	 * @param string $id
	 * @param string $group
	 */
	protected function __construct($id = "", $group = "") {
		$this->id		=	 $id;
		$this->group	=	 $group;
	}

	/**
	 * pretizeno o vynechani disabled records
	 * @param bool $groupOnly
	 * @param string $mode
	 */
	public function clean($groupOnly = true, $mode = "ingroup") {
		try {
			if ($this->group == "acces") return;
			parent::clean($groupOnly, $mode);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>