<?php

require_once(LBOX_PATH_CORE_CLASSES . SLASH ."exceptions". SLASH ."class.LBoxExceptionCache.php");

/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0

 * @date 2008-02-13
 */
class LBoxCache
{
	/**
	 * @var string
	 */
	protected $fileName = "";

	/**
	 * data cache v poli array[key] = value
	 * @var array
	 */
	protected $data = array();

	/**
	 * cache metody getFileP()
	 */
	protected $fileR;

	/**
	 * cache zmenena/nezmenena (kuli vyhnuti se zbytecnemu zapisovani cache na disk)
	 * @var bool
	 */
	protected $changed;

	protected static $instance;
	
	protected static $filesOpenedRead 	= 0;
	protected static $filesOpenedWrite 	= 0;
	protected static $filesDeleted		= 0;

	/**
	 * debug verbose
	 * @var bool
	 */
	public static $debug	= false;
	
	/**
	 * @param string fileName - custom filename
	 * @return AccesRecord
	 * @throws Exception
	 */
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
	
	protected function __construct() {}

	/**
	 * fileName setter
	 * @param string $fileName
	 */
	public function setFileName($fileName	= "") {
		if (strlen($fileName) < 1) {
			throw new LBoxExceptionCache(LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
		}
		$this->fileName	= $fileName;
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
	 * vymaze cash
	 * @throws LBoxExceptionCache
	 */
	public function reset () {
		try {
			$this->data	= array();
			self::$filesDeleted++;
			$this->debug("DELETING cache in '". $this->getFilePath() ."'");
			@unlink($this->getFilePath());
			$this->changed	= true;
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
/*if (file_exists($this->getFilePath())) {
	var_dump($this->getFilePath() ." existuje");
}
else {
	var_dump($this->getFilePath() ." NEexistuje");
}*/
			return file_exists($this->getFilePath());
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
	 * uklada hodnotu do cache
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
			if ($value)
			if (array_key_exists($key, $this->data) && $this->data[$key]	== $value) {
				return;
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
	protected function getData () {
		try {
			if (count($this->data) > 0) {
				return $this->data;
			}
			if (!file_exists($this->getFilePath())) {
				return "";
			}
			if (filesize($this->getFilePath()) < 1) {
				return "";
			}

			$this->debug("READING cache data from '". $this->getFilePath() ."'");
			$data	= fread($this->getFileR(), filesize($this->getFilePath()));
			if (!$data) {
				$this->debug("CACHE READ ERROR!!!\n In '". __FILE__ ."' at line ". __LINE__);
				//throw new LBoxExceptionCache(LBoxExceptionCache::MSG_CACHE_CANNOT_READ, LBoxExceptionCache::CODE_CACHE_CANNOT_READ);
			}
			fclose($this->fileR);unset($this->fileR);
			$this->data	= unserialize($data);
			$this->changed	= false;
			return $this->data;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected static $uz = false;
	/**
	 * uklada nacachovana data, pokud nejaka jsou
	 * @throws LBoxExceptionCache
	 */
	public function saveCachedData () {
		try {
			if (!$this->changed) {
				return;
			}
			$fileW	= $this->getFileW();
			if (!fwrite($fileW, serialize($this->data))) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_CACHE_CANNOT_WRITE, LBoxExceptionCache::CODE_CACHE_CANNOT_WRITE);
			}
			$this->debug("WRITING cache data into '". $this->getFilePath() ."'");
			fclose($fileW);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci file pointer cache filu pro cteni
	 * @return resource
	 * @throws LBoxExceptionCache
	 */
	private function getFileR () {
		try {
			if (!is_resource($this->fileR)) {
				if (!$this->fileR	= fopen($this->getFilePath(), "r")) {
					throw new LBoxExceptionCache(LBoxExceptionCache::MSG_CACHE_CANNOT_OPEN_READ, LBoxExceptionCache::CODE_CACHE_CANNOT_OPEN_READ);
				}
				self::$filesOpenedRead++;
			}
			return $this->fileR;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci file pointer cache filu pro zapis noveho obsahu (smaze puvodni obsah)
	 * @return resource
	 * @throws LBoxExceptionCache
	 */
	private function getFileW () {
		try {
			if (!$fileW	= fopen($this->getFilePath(), "w")) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_CACHE_CANNOT_OPEN_WRITE, LBoxExceptionCache::CODE_CACHE_CANNOT_OPEN_WRITE);
			}
			self::$filesOpenedWrite++;
			return $fileW;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci kompletni cestu k filu
	 * @return string
	 * @throws LBoxExceptionCache
	 */
	protected function getFilePath () {
		try {
			if (strlen($this->fileName) < 1) {
				throw new LBoxExceptionCache("\$fileName". LBoxExceptionCache::MSG_REQUIRED_ATTR_NOT_DEFINED, LBoxExceptionCache::CODE_REQUIRED_ATTR_NOT_DEFINED);
			}
			$path	= LBOX_PATH_CACHE . SLASH . str_replace("/", SLASH, $this->fileName);
			// pokud adresar neexistuje, vytvorime ho
			if (!is_dir(dirname($path))) {
				$this->createDirByPath(dirname($path));
			}
			return $path;
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
	protected function createDirByPath($path = "") {
		try {
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
					if (!mkdir($pathTMP)) {
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

	protected function debug($msg) {
		if (!self::$debug) return;
		switch (true) {
			/*case is_numeric(strpos($msg, 'DELETING cache in \'E:\www\www.techhouse.cz\project\.cache\abstractrecord\acces\\')):
					throw new LBoxException("sdfsdfds");
				break;*/
			
			case is_numeric(strpos($msg, "WRITING")):
			case is_numeric(strpos($msg, "DELETING")):
					$count	= self::$filesOpenedWrite + self::$filesDeleted;
					$bg		= "#835554";
				break;
			case is_numeric(strpos($msg, "READING")):
					$count	= self::$filesOpenedRead;
					$bg	= "#54836D";
				break;
			default:
					$bg	= "#546983";
		}
		$color	= "#ffffff";
		$msg 	= "<table><th bgcolor='$bg' align='left'><b><font color='$color'>". nl2br($count .": ". $msg) ."</font></b></th></table>\n";
		echo $msg;
		flush();
	}
}
?>