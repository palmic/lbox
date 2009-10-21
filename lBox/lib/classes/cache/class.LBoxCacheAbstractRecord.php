<?php

/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @date 2008-10-02
 */
class LBoxCacheAbstractRecord extends LBoxCache
{
	protected static $filePath	= "abstractrecord/";
	
	protected $fileName	= "";

	protected static $instances;

	/**
	 * @return LBoxCacheAbstractRecord
	 * @throws LBoxExceptionCache
	 */
	public static function getInstance ($fileName	= "") {
		$className 	= __CLASS__;
		try {
			$filePath	= self::$filePath . $fileName;
			$key		= md5($filePath);
			if (!array_key_exists($key, (array)self::$instances) || (!self::$instances[$key] instanceof $className)) {
				self::$instances[$key] = new $className;
			}
			self::$instances[$key]->setFileName($filePath);
			return self::$instances[$key];
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * AbstractRecord data getter
	 * @return array
	 */
	public function getData	() {
		try {
			return (array)parent::getData();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * clear all cache data
	 */
	public function clearCache() {
		try {
			$this->reset();
			LBoxUtil::emptyDirByPath(dirname($this->getFilePath()), true);
/*echo "<hr>";
var_dump("v adresari '$path' zbylo:");
$dir	= dir($path);
while (false !== ($entry = $dir->read())) {
var_dump($path .SLASH. $entry);
}
$dir->close();
echo "<hr>";
self::$uz = true;*/
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>