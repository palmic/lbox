<?php

require_once(LBOX_PATH_CORE_CLASSES . SLASH ."exceptions". SLASH ."class.LBoxExceptionCache.php");

/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0

 * @date 2008-02-13
 */
abstract class LBoxCache
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
	protected static $changed;

	protected static $instance;
	
	/**
	 * @return AccesRecord
	 * @throws Exception
	 */
	abstract public static function getInstance();

	protected function __construct() {}

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

	public function __destruct() {
		try {
			$this->saveCachedData();
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
			$this->__destruct();
			
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
			$data	= $this->getData();
			// a vratime hodnotu
			return $data[$key];
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
			if (is_null($value)) {
				throw new LBoxExceptionCache("\$value: ". LBoxExceptionCache::MSG_PARAM_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			if ($this->data[$key]	== $value) {
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
	private function getData () {
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
			$data	= fread($this->getFileR(), filesize($this->getFilePath()));
			if (!$data) {
				//throw new LBoxExceptionCache(LBoxExceptionCache::MSG_CACHE_CANNOT_READ, LBoxExceptionCache::CODE_CACHE_CANNOT_READ);
			}
			$this->data	= unserialize($data);
			$this->changed	= false;
			return $this->data;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * uklada nacachovana data, pokud nejaka jsou
	 * @throws LBoxExceptionCache
	 */
	private function saveCachedData () {
		try {
			if (!$this->changed) {
				return;
			}
			$fileW	= $this->getFileW();
			if (!fwrite($fileW, serialize($this->data))) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_CACHE_CANNOT_WRITE, LBoxExceptionCache::CODE_CACHE_CANNOT_WRITE);
			}
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
	private function getFilePath () {
		try {
			if (strlen($this->fileName) < 1) {
				throw new LBoxExceptionCache("\$fileName". LBoxExceptionCache::MSG_REQUIRED_ATTR_NOT_DEFINED, LBoxExceptionCache::CODE_REQUIRED_ATTR_NOT_DEFINED);
			}
			return LBOX_PATH_CACHE . SLASH . $this->fileName;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>