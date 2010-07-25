<?php

/**
 * trida starajici se o obecne prostredky nutne k provozu front cache
 * @author Michal Palma <michal.palma@gmail.com>
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
	protected $recordTypes;

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
	 * prida form do indexace k aktualni URL
	 * @param mixed $component
	 */
	public function addComponentUsed($component) {
		try {
			if ($this->listeningOff) {
				return;
			}
			$componentID	= ($component instanceof LBoxComponent) ? $component->id : $component;
			$url	= (substr(LBOX_REQUEST_URL, -1) == "/") ? LBOX_REQUEST_URL : LBOX_REQUEST_URL . "/";
			$url	= str_replace("?/", "/", $url);
			$url	= str_replace("//", "/", $url);
			if (LBoxFront::getPage()->config->id != LBoxConfigSystem::getInstance()->getParamByPath("pages/page404")) {
				$this->recordTypes[$url]["components"][$componentID]	= $componentID;
				$this->recordTypes[$url]["pageid"]							= LBoxFront::getPage()->config->id;
			}
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
			if ($type == "AccesRecord") return;
			$url	= (substr(LBOX_REQUEST_URL, -1) == "/") ? LBOX_REQUEST_URL : LBOX_REQUEST_URL . "/";
			$url	= str_replace("?/", "/", $url);
			$url	= str_replace("//", "/", $url);
			if (LBoxFront::getPage()->config->id != LBoxConfigSystem::getInstance()->getParamByPath("pages/page404")) {
				$this->recordTypes[$url]["recordtypes"][$type]	= $type;
				$this->recordTypes[$url]["pageid"]				= LBoxFront::getPage()->config->id;
			}
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
			$url	= (substr(LBOX_REQUEST_URL, -1) == "/") ? LBOX_REQUEST_URL : LBOX_REQUEST_URL . "/";
			$url	= str_replace("?/", "/", $url);
			$url	= str_replace("//", "/", $url);
			if (LBoxFront::getPage()->config->id != LBoxConfigSystem::getInstance()->getParamByPath("pages/page404")) {
				$this->recordTypes[$url]["forms"][$form->getName()]	= $form->getName();
				$this->recordTypes[$url]["pageid"]					= LBoxFront::getPage()->config->id;
			}
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
			$url	= (substr(LBOX_REQUEST_URL, -1) == "/") ? LBOX_REQUEST_URL : LBOX_REQUEST_URL . "/";
			$url	= str_replace("?/", "/", $url);
			$url	= str_replace("//", "/", $url);
			foreach ((array)$this->recordTypes[$url]["forms"] as $formName) {
				if (LBoxForm::wasFormSentByName($formName)) {
					return true;
				}
//LBoxFirePHP::error("$formName nebyl odeslan!");
			}
			return false;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vycisti cache relevantni k danemu typu recordu
	 * @param mixed $component
	 * @param bool $forceCleanForAllXTUsers
	 */
	public function cleanByComponent($component, $forceCleanForAllXTUsers = false) {
		try {
			$componentID	= ($component instanceof LBoxComponent) ? $component->id : $component;
			foreach ((array)$this->recordTypes as $url => $recordInfo) {
				if (!$url) {continue;}
				foreach ((array)$recordInfo["components"] as $componentAdded) {
					if ($componentAdded == $component) {
						$this->cleanURLData($url, $forceCleanForAllXTUsers);
					}
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * cache var
	 * @var array
	 */
	protected static $cleanedByRecordType	= array();

	/**
	 * vycisti cache relevantni k danemu typu recordu
	 * @param string $type
	 * @param bool $forceCleanForAllXTUsers
	 */
	public function cleanByRecordType($type = "", $forceCleanForAllXTUsers = false) {
		try {
			if (strlen($type) < 1) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			$key	= md5($type . $forceCleanForAllXTUsers);
			if (array_key_exists($key, self::$cleanedByRecordType)) {
				return;
			}
//LBoxFirePHP::error("mazu $type ". (int)$forceCleanForAllXTUsers);
			if ($type == "AccesRecord") return;
			foreach ((array)$this->recordTypes as $url => $recordInfo) {
				if (!$url) {continue;}
				foreach ((array)$recordInfo["recordtypes"] as $recordType) {
					if ($recordType == $type) {
						try {
							$this->cleanURLData($url, $forceCleanForAllXTUsers);
						}
						catch (Exception $e) {
/*if (is_numeric(strpos(LBOX_REQUEST_URL_HOST, "beta.")) || is_numeric(strpos(LBOX_REQUEST_URL_HOST, "localhost."))) {
	throw $e;
}*/
							if ($e->getCode() == 3005) {
								NULL;
							}
							else {
								throw $e;
							}
						}
					}
				}
			}
			self::$cleanedByRecordType[$key]	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vymaze cache vsech URLs
	 * @param bool $forceCleanForAllXTUsers
	 */
	public function cleanAllURLs($forceCleanForAllXTUsers = false) {
		try {
			foreach ((array)$this->recordTypes as $url => $recordType) {
				if (!$url) {
					continue;
				}
				$this->cleanURLData($url, $forceCleanForAllXTUsers);
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
			foreach ((array)$this->recordTypes as $url => $recordType) {
				if (!$url) {
					continue;
				}
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
			if (!array_key_exists($url, (array)$this->recordTypes)) {
				return;
			}
			
			$config	= LBoxConfigManagerStructure::getInstance()->getPageById($this->recordTypes[$url]["pageid"]);
			if ((!$forceCleanForAllXTUsers) && (!LBoxXTProject::isLoggedAdmin()) && $config->cache_by_xtuser) {
				LBoxCacheFront::getInstance("", "", $this->isPageCachedByXTUserByURL($url))->removeConcrete(LBoxCacheFront::getCacheID(), $url);
			}
			else {
				if ($this->isPageCachedByXTUserByURL($url)) {
					LBoxCacheFront::getInstance("", "", $this->isPageCachedByXTUserByURL($url))->cleanConcrete($url);
				}
				else {
					LBoxCacheFront::getInstance($url, LBoxCacheFront::getCacheID(), $this->isPageCachedByXTUserByURL($url))->removeConcrete();
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vrati, jestli je stranka cachovana pro kazdeho XTUsera zvlast 
	 * @param bool
	 */
	protected function isPageCachedByXTUserByURL($url = "") {
		try {
			return (bool)$this->getPageCFGByURL($url)->cache_by_xtuser;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vrati instanci configu stranky podle URL
	 * @param string $url
	 * @return LBoxConfigItemStructure
	 */
	protected function getPageCFGByURL($url = "") {
		try {
			if (strlen($url) < 1) {
				$url	= (substr(LBOX_REQUEST_URL, -1) == "/") ? LBOX_REQUEST_URL : LBOX_REQUEST_URL . "/";
				$url	= str_replace("?/", "/", $url);
				$url	= str_replace("//", "/", $url);
			}
			// vycistime URL od parametru a hostu
			$url		= preg_replace("/".LBOX_REQUEST_URL_SCHEME.":(\/+)/", "", $url);
			$url		= substr($url, strpos($url, "/"));
			$url		= preg_replace("/(\?|\:)(.+)/", "", $url);
			
			$displayLanguage	= LBoxFront::getDisplayLanguage();
			foreach (LBoxConfigManagerLangdomains::getInstance()->getLangsDomains() as $lang => $domain) {
				try {
					LBoxFront::setDisplayLanguage($lang);
					$configItemStructure	= LBoxConfigManagerStructure::getInstance()->getPageByUrl($url);
					if ($configItemStructure instanceof LBoxConfigItemStructure) {
						break;
					}
				}
				catch (Exception $e) {
					$exception	= $e;
				}
			}
			LBoxFront::setDisplayLanguage($displayLanguage);
			return $configItemStructure;
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
			$this->recordTypes	= array();
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
			return LBoxCacheFront::getInstance("", "", $this->isPageCachedByXTUserByURL())->getDataDirect();
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
			LBoxCacheFront::getInstance("", "", $this->isPageCachedByXTUserByURL())->saveDataDirect($data);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * cache var
	 * @var bool
	 */
	protected $doesCacheExists;
	
	/**
	 * LBoxCacheFront alias
	 * @return bool
	 */
	public function doesCacheExists() {
		try {
			if (is_bool($this->doesCacheExists)) {
				return $this->doesCacheExists;
			}
			return $this->doesCacheExists = LBoxCacheFront::getInstance("", "", $this->isPageCachedByXTUserByURL())->doesCacheExists();
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
			return LBoxCacheFront::getInstance("", "", $this->isPageCachedByXTUserByURL())->getLastCacheModificationTime();
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

	/**
	 * vraci, jestli je cache na aktualni strance zapnuta
	 * @return bool
	 */
	public function isCacheON() {
		try {
			switch (true) {
				case (!LBoxConfigManagerProperties::gpcn("cache_front")):
				case LBoxFront::getPage()->cache_off:
				case (LBoxFront::getPage()->cache_only_notlogged && LBoxXTProject::isLogged()):
						return false;
					break;
				default:
					return true;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected static $instance;

	/**
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
			$this->recordTypes	= (array)unserialize($this->getCache()->getDataDirect());
			ksort($this->recordTypes);
LBoxFirePHP::table($this->recordTypes, "data v cachi indexovani URLs vs records types (". count($this->recordTypes) .")");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>