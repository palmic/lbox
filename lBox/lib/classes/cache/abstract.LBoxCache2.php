<?php

// pri loadu cache filesystemu jeste neni nacten loader
require_once(LBOX_PATH_CORE_CLASSES . SLASH ."exceptions". SLASH ."class.LBoxExceptionCache.php");

/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @date 2010-04-08
 */
class LBoxCache2
{
	/**
	 * defaultni zivotnost cache
	 * @var int
	 */
	protected $lifeTime = 3600;
	
	/**
	 * see http://pear.php.net/manual/en/package.caching.cache-lite.cache-lite.cache-lite.php
	 * @var int
	 */
	protected $hashedDirectoryLevel = 1;
	
	/**
	 * cache data ID (see Cache_Lite)
	 * @var string
	 */
	protected $id;
	
	/**
	 * cache data group (see Cache_Lite)
	 * @var string
	 */
	protected $group = "";
	
	/**
	 * data cache v poli array[key] = value
	 * @var array
	 */
	protected $data = array();

	/**
	 * cache zmenena/nezmenena (kuli vyhnuti se zbytecnemu zapisovani cache na disk)
	 * @var bool
	 */
	protected $changed;

	/**
	 * instance tridy managujici skutecny caching
	 * @var Cache_Lite
	 */
	protected $cache;
	
	/**
	 * pocitadlo souboru otevrenych pro cteni
	 * @var int
	 */
	protected static $filesOpenedRead	= 0;
	
	/**
	 * pocitadlo souboru otevrenych pro zapis
	 * @var int
	 */
	protected static $filesOpenedWrite	= 0;

	/**
     * Cache_Lite Umask for hashed directory structure
	 * @var int
	 */
	protected static $_hashedDirectoryUmask	= 0777;

	protected static $instances = array();

	/**
	 * @return LBoxCacheLoader
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
	 * @param string $id
	 * @param string $group
	 */
	protected function __construct($id = "", $group = "") {
		if (strlen($id) < 1) {
			throw new LBoxExceptionCache("\$id: ". LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
		}
		$this->id		=	 $id;
		$this->group	=	 $group;
	}

	/**
	 * getter na hodnotu
	 * @param string $key
	 * @return mixed
	 * @throws LBoxExceptionCache
	 */
	public function __get($key = "") {
		try {
			return $this->getValue($key);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na hodnotu
	 * @param string $key
	 * @param mixed $value
	 * @throws LBoxExceptionCache
	 */
	public function __set($key = "", $value = NULL) {
		try {
			return $this->saveValue($key, $value);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vymaze cash pro momentalni ID a group
	 * @throws LBoxExceptionCache
	 */
	public function reset () {
		try {
			$this->removeConcrete();
			$this->data	= array();
			$this->changed	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vymaze aktualni cash podle parametru
	 * @param bool $groupOnly
	 * @param string $mode ingroup | notingroup | old  callback_myFunc - see http://pear.php.net/manual/en/package.caching.cache-lite.cache-lite.clean.php
	 * @throws LBoxExceptionCache
	 */
	public function clean ($groupOnly = true, $mode = "ingroup") {
		try {
			$this->cleanConcrete($groupOnly ? $this->group : false, $mode);
			$this->data	= array();
			$this->changed	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vymaze libovolne id z cache podle parametru
	 * @param string $id
	 * @param string $group
	 */
	public function removeConcrete($id = "", $group = "") {
		try {
			if (strlen($id) < 1) {$id = $this->id;}
			if (strlen($group) < 1) {$group = $this->group;}
			if (!$this->getCache()->remove($id, $group, true)) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_CACHE_CANNOT_WRITE, LBoxExceptionCache::CODE_CACHE_CANNOT_WRITE);
			}
LBoxFirePHP::warn("cache smazana: \$id='$id', \$group='$group' \$dir='". $this->getDir() ."'");
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vycisti libovolnou groupu cache podle parametru
	 * @param string $group
	 * @param string $mode ingroup | notingroup | old  callback_myFunc - see http://pear.php.net/manual/en/package.caching.cache-lite.cache-lite.clean.php
	 */
	public function cleanConcrete($group = false, $mode = "ingroup") {
		try {
			if (!$this->getCache()->clean($group, $mode)) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_CACHE_CANNOT_WRITE, LBoxExceptionCache::CODE_CACHE_CANNOT_WRITE);
			}
LBoxFirePHP::warn("cache smazana: \$group='$group' \$dir='". $this->getDir() ."'");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vymaze jednu hodnotu z cashe
	 * @throws LBoxExceptionCache
	 */
	public function resetValue ($key = "") {
		try {
			if (strlen($key) < 1) {
				throw new LBoxExceptionCache("\$key: ". LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			// kuli zarucenemu nacteni cache
			$this->getValue($key);
			unset($this->data[$key]);
				
			$this->changed	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci true, pokud cache existuje
	 * @return bool
	 * @throws LBoxException
	 */
	public function doesCacheExists() {
		try {
			return (bool)$this->getDataDirect();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na pocet files otevrenych pro cteni
	 * @return int
	 */
	public function getFilesOpenedRead() {
		return self::$filesOpenedRead;
	}

	/**
	 * getter na pocet files otevrenych pro zapis
	 * @return int
	 */
	public function getFilesOpenedWrite() {
		return self::$filesOpenedWrite;
	}

	/**
	 * getter na cas posledni modifikace cache
	 * @return int
	 */
	public function getLastCacheModificationTime() {
		try {
			return $this->getCache()->lastModified();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci hodnotu do cache
	 * @param string $key
	 * @param mixed $value
	 * @throws LBoxExceptionCache
	 */
	protected function getValue ($key = "") {
		try {
			if (strlen($key) < 1) {
				throw new LBoxExceptionCache("\$key: ". LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			// loadneme data
			$data	= 	$this->getData();
			return		array_key_exists($key, (array)$data) ? $data[$key] : NULL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * uklada hodnotu do cache
	 * @param string $key
	 * @param mixed $value
	 * @throws LBoxExceptionCache
	 */
	protected function saveValue ($key = "", $value = NULL) {
		try {
			if (strlen($key) < 1) {
				throw new LBoxExceptionCache("\$key: ". LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			// loadneme data
			$this->getData();
			// a zapiseme tam hodnotu
			$this->data[$key]	= $value;
				
			$this->changed	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci nacachovana data, pokud nejaka jsou
	 * @return array
	 * @throws LBoxExceptionCache
	 */
	public function getData () {
		try {
			if (count($this->data) > 0) {
				return $this->data;
			}
			return $this->data = (array)(($data = $this->getDataDirect()) ? unserialize($data) : $data);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * cache var
	 * @var string
	 */
	protected $dataDirect;

	/**
	 * vraci surova nacachovana data
	 * @return string
	 */
	public function getDataDirect () {
		try {
			if (is_string($this->dataDirect)) {
				return $this->dataDirect;
			}
//LBoxFirePHP::log("vracim data z cache group = '". $this->group ."' id = '".$this->id."'");
			if (!$data = $this->getCache()->get($this->id, $this->group ? $this->group : NULL)) {
				return $this->dataDirect = "";
			}
			self::$filesOpenedRead++;
			return $this->dataDirect = $data;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * uklada nacachovana data, pokud nejaka jsou
	 * @throws LBoxExceptionCache
	 */
	public function saveCachedData () {
		try {
			if (!$this->changed) return;
			$this->saveDataDirect(serialize($this->data));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * direct data storing method
	 * @param string $data
	 * @param string $id
	 * @param string $group
	 */
	public function saveDataDirect($data = "") {
		try {
			if (!$this->getCache()->save($data, $this->id, $this->group ? $this->group : NULL)) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_CACHE_CANNOT_WRITE, LBoxExceptionCache::CODE_CACHE_CANNOT_WRITE);
			}
			$this->dataDirect = NULL;
			self::$filesOpenedWrite++;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci adresar cachovani
	 * @return string
	 * @throws LBoxExceptionCache
	 */
	protected function getDir () {
		try {
			return LBOX_PATH_CACHE;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na instanci komponenty starajici se o samotne cachovani
	 * @return Cache_Lite
	 */
	protected function getCache() {
		try {
			if ($this->cache instanceof Cache_Lite) {
				return $this->cache;
			}
			$dir	= LBoxUtil::fixPathSlashes($this->getDir());
			$dir	=  substr($dir, -1) == SLASH ? $dir : $dir . SLASH;
			self::createDirByPath($dir);
			$cacheOptions = array(
			    "cacheDir" 				=> $dir,
			    "lifeTime" 				=> $this->lifeTime,
			    "hashedDirectoryLevel"	=> $this->hashedDirectoryLevel,
			);
			if (WIN) {
				$cacheOptions["fileLocking"]			= false;
				$cacheOptions["writeControl"]			= false;
				$cacheOptions["readControl"]			= false;
				$cacheOptions["hashedDirectoryLevel"]	= 0;
			}
			$this->cache	= new Cache_Lite($cacheOptions);
			$this->cache	->_hashedDirectoryUmask	= self::$_hashedDirectoryUmask;
			return $this->cache;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vytvori adresar podle predane cesty
	 * @param string $path
	 * @throws LBoxExceptionFilesystem
	 */
	protected static function createDirByPath($path = "") {
		try {

			// !!! delegace na LBoxUtil neni mozna, protoze pri loadu cache filesystemu jeste neni LBoxUtil nacetla !!!
			
			if (strlen($path) < 1) {
				throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFilesystem::CODE_BAD_PARAM);
			}
			$path		= str_replace("/", SLASH, $path);
			$path		= str_replace("\\", SLASH, $path);
			$pathParts	 = explode(SLASH, $path);
			$pathTMP	 = WIN ? "" : "/";
			if (is_dir($path)) return;
			$i	= 1;
			foreach ($pathParts as $pathPart) {
				if (strlen($pathPart) < 1) continue;
				if (WIN) 	$pathTMP	.= strlen($pathTMP) > 0 ? SLASH ."$pathPart" : $pathPart;
				else 		$pathTMP	.= strlen($pathTMP) > 1 ? SLASH ."$pathPart" : $pathPart;
				if (strlen(strstr($pathPart, ":")) > 0) continue;
				$i++;
				if ($i <= count(explode(SLASH, LBOX_PATH_INSTANCE_ROOT))) continue;
				if (!is_dir($pathTMP)) {
					if (file_exists($pathTMP)) {
						throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_FILE_ALREADY_EXISTS, LBoxExceptionFilesystem::CODE_FILE_ALREADY_EXISTS);
					}
					if (!@mkdir($pathTMP)) {
						throw new LBoxExceptionFilesystem(	$pathTMP .": ". LBoxExceptionFilesystem::MSG_DIRECTORY_CANNOT_CREATE,
															LBoxExceptionFilesystem::CODE_DIRECTORY_CANNOT_CREATE);
					}
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>