<?php

/**
 * trida starajici se o obecne prostredky nutne k provozu front cache
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @date 2010-04-08
 */
class LBoxCacheManagerFront
{
	/**
	 * cache var
	 * @var LBoxCacheData
	 */
	private static $cache;

	/**
	 * pole indexujici vztah URLs a recordu array(url => array(record1, record2.....))
	 * @var array
	 */
	protected $recordTypes	= array();

	/**
	 * cache var
	 * @var array
	 */
	protected $uRLsByrecordTypes	= array();

	/**
	 * flag pro vypnuti naslouchani v pripadech, kdy nechceme logovat vazby URLs a records types (nutne pro API)
	 * @var bool
	 */
	protected $listeningOff			= false;

	/**
	 * prepina naslouchani records
	 * @param bool $value
	 */
	public function switchListeningOff($value = true) {
		try {
			$this->listeningOff	= (bool)$value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * prida typ rekordu do indexace k aktualni URL
	 * @param string $type
	 */
	public function addRecordType($type = "") {
		try {
			if (strlen($type) < 1) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			if ($this->listeningOff) {
				return;
			}
			if ($type == AccesRecord) return;
			$this->recordTypes[LBOX_REQUEST_URL]["recordtypes"][$type]	= $type;
			$this->recordTypes[LBOX_REQUEST_URL]["pageid"]				= LBoxFront::getPage()->config->id;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * prida form do indexace k aktualni URL
	 * @param LBoxForm $form
	 */
	public function addFormUsed(LBoxForm $form) {
		try {
			if ($this->listeningOff) {
				return;
			}
			$this->recordTypes[LBOX_REQUEST_URL]["forms"][$form->getName()]	= $form->getName();
			$this->recordTypes[LBOX_REQUEST_URL]["pageid"]					= LBoxFront::getPage()->config->id;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vrati true, pokud byl na teto strance aktualne odeslan formular - pouzivano pri zjitovani jestli se ma tato URL nacist z cache
	 * @return bool
	 */
	public function wasFormSentNow() {
		try {
			foreach ((array)$this->recordTypes[LBOX_REQUEST_URL]["forms"] as $formName) {
				if (LBoxForm::wasFormSentByName($formName)) {
					return true;
				}
			}
			return false;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	/**
	 * vycisti cache relevantni k danemu typu recordu
	 * @param string $type
	 */
	public function cleanByRecordType($type = "") {
		try {
			if (strlen($type) < 1) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			if ($type == "AccesRecord") return;
			foreach ($this->getURLsByRecordType($type) as $url) {
				$this->cleanURLData($url);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vymaze cache stranky podle predaneho id, nebo current stranky pokud zadne ID predano nebylo
	 * @param int $pageID
	 * @param bool $forceCleanForAllXTUsers
	 */
	public function cleanByPageID($pageID = "", $forceCleanForAllXTUsers = false) {
		try {
			if (strlen($pageID) < 1) {$pageID = LBoxFront::getPage()->config->id;}
			foreach ($this->recordTypes as $url => $recordType) {
				if ($recordType["pageid"] == $pageID) {
					$this->cleanURLData($url, $forceCleanForAllXTUsers);
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vymaze cache data konkretni URL pricemz si sam zkontroluje, jestli ma byt stranka na teto URL promazana jen pro momentalne zalogovaneho uzivatele
	 * @param string $url
	 * @param bool $forceCleanForAllXTUsers
	 */
	public function cleanURLData($url = "", $forceCleanForAllXTUsers = false) {
		try {
			if (strlen($url) < 1) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			if (!array_key_exists($url, $this->recordTypes)) {
				return;
			}

			$config	= LBoxConfigManagerStructure::getInstance()->getPageById($this->recordTypes[$url]["pageid"]);
			if ((!$forceCleanForAllXTUsers) && (!LBoxXTProject::isLoggedAdmin(XT_GROUP ? XT_GROUP : NULL)) && $config->cache_recordsdata_by_xtuser) {
				LBoxCacheFront::getInstance()->removeConcrete(LBoxCacheFront::getCacheID(), $url);
			}
			else {
				LBoxCacheFront::getInstance()->cleanConcrete($url);
			}
			unset($this->recordTypes[$url]);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vymaze veskere vazby URLs a types
	 */
	public function reset() {
		try {
			$this->recordTypes	= array(array());
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vrati nacachovana Front data momentalne zobrazovane URL
	 * @return string
	 */
	public function getData() {
		try {
			return LBoxCacheFront::getInstance()->getDataDirect();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * nacachuje Front data momentalne zobrazovane URL
	 * @param string $data
	 */
	public function saveData($data = "") {
		try {
			if (!is_string($data)) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			LBoxCacheFront::getInstance()->saveDataDirect($data);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * LBoxCacheFront alias
	 * @return bool
	 */
	public function doesCacheExists() {
		try {
			return LBoxCacheFront::getInstance()->doesCacheExists();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * LBoxCacheFront alias
	 * @return int
	 */
	public function getLastCacheModificationTime() {
		try {
			return LBoxCacheFront::getInstance()->getLastCacheModificationTime();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vrati vsechny URL indexovane s predanym typem
	 * @param string $type
	 * @return array
	 */
	protected function getURLsByRecordType($type = "") {
		try {
			if (strlen($type) < 1) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			if (array_key_exists($type, $this->uRLsByrecordTypes) && count($this->uRLsByrecordTypes[$type]) > 0) {
				return $this->uRLsByrecordTypes[$type];
			}
			$this->uRLsByrecordTypes[$type]	= array();
			foreach ($this->recordTypes as $url => $recordInfo) {
				foreach ((array)$recordInfo["recordtypes"] as $recordType) {
					$this->uRLsByrecordTypes[$recordType][]	= $url;
				}
			}
			return $this->uRLsByrecordTypes[$type];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na instanci cache
	 * @return LBoxCacheData
	 */
	protected function getCache() {
		try {
			if (self::$cache instanceof LBoxCacheData) {
				return self::$cache;
			}
			self::$cache	= LBoxCacheData::getInstance("front", "", LBoxConfigSystem::getInstance()->getParamByPath("output/cache/path"));
			self::$cache	->setAutoSave(false);
			return self::$cache;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected static $instance;

	/**
	 * @return LBoxCacheLoader
	 * @return LBoxCacheManagerFront
	 */
	public static function getInstance () {
		$className 	= __CLASS__;
		try {
			if (self::$instance instanceof $className) {
				return self::$instance;
			}
			return self::$instance = new $className();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * flag
	 * @var bool
	 */
	protected $destructed = false;
	
	public function __destruct() {
		try {
			if ($this->destructed) return;
			// ulozeni cache
			$this->getCache()->saveDataDirect(serialize($this->recordTypes));
			$this->destructed = true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function __construct() {
		try {
			// nacteni dat z cache do pameti
			$this->recordTypes	= unserialize($this->getCache()->getDataDirect());
LBoxFirePHP::table((array)$this->recordTypes, "data v cachi indexovani URLs vs records types");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>