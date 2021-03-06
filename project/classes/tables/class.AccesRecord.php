<?php
/**
 * Pozor! pouziva se jako singleton presto ze ma public constructor - kuli dedicnosti
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2007-12-08
*/
class AccesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "AccesRecords";
	public static $tableName    	= "acces";
	public static $idColName    	= "id";
	
	public static $dependingRecords	= array(
/*	"CasinoGamesAccessRecord",
	"CreditTransfersRecord",
	"InquiriesOptionsResponsesRecord",
	"LBoxShopingCartsXTUsersRecord",
	"LBoxShopingOrdersItemsXTUsersRecord",
	"LBoxShopingNCCartsXTUsersRecord",
	"TokensAccesRecord",*/
	);
	
	/**
	 * @var AccesRecord
	 */
	protected static $instance;
	
	/**
	 * blokovany setter - cely zaznam se nastavuje sam pomoci verejne konstanty $_SERVER
	 * @param string $name
	 * @param string $value
	 */
	public function __set($name, $value) {
	}

	/**
	 * @param bool $forceDoNotCheckLogin
	 * Pozor! pouziva se jako singleton presto ze ma public constructor - kuli dedicnosti
	 */
	public function __construct($forceDoNotCheckLogin = false) {
		$className	= __CLASS__;
		if (AccesRecord::$instance instanceof $className) {
			//throw new LBoxExceptionFront(LBoxExceptionFront::MSG_ACCES_MULTIPLE_INSTANCES, LBoxExceptionFront::CODE_ACCES_MULTIPLE_INSTANCES);
		}
		$this->params["time"] 				= date("Y-m-d H:i:s");
		$this->params["ip"] 				= LBOX_REQUEST_IP;
		$this->params["url"] 				= LBOX_REQUEST_URL;
		$this->params["referer"] 			= LBOX_REQUEST_REFERER;
		$this->params["agent"] 				= LBOX_REQUEST_AGENT;
		if ((!$forceDoNotCheckLogin) && LBoxXT::isLogged()) {
			$this->params["ref_xtuser"]		= LBoxXT::getUserXTRecord()->id;
		}
		
	}

	/**
	 * do not use cache
	 */
	public function isCacheOn() {return false;}
	
	/**
	 * @param bool $forceDoNotCheckLogin
	 * @return AccesRecord
	 * @throws Exception
	 */
	public static function getInstance($forceDoNotCheckLogin = false) {
		try {
			if (!self::$instance instanceof AccesRecord) {
				self::$instance = new AccesRecord($forceDoNotCheckLogin);
				self::$instance	->store();
			}
			return self::$instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * autosave - pohodlnejsi nez nutne volat store
	 * @throws Exception
	 */
	public function __destruct() {
		try {
			if (LBoxConfigManagerProperties::getPropertyContentByName("log_access") < 1) {
				return;
			}
			$this->params["queries"]			= DbControl::getQueryCount()+1;
			$this->params["time_execution"]		= LBoxTimer::getInstance()->getTimeOfLife();
			$this->params["cache_read"]			= LBoxCache::getInstance()->getFilesOpenedRead();
			$this->params["cache_write"]		= LBoxCache::getInstance()->getFilesOpenedWrite();
			$this->params["memory"]				= memory_get_peak_usage(true);
			$this->params["memory_limit"]		= ini_get("memory_limit")*1024*1024;
			$this->synchronized	= false;
			$this->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function store() {
		try {
			// v pripade, ze nepouzivame databazi, access se neuklada
			if (LBoxConfigManagerProperties::getPropertyContentByName("log_access") < 1) {
				return;
			}
			if ((!array_key_exists("request_time", $this->params)) || (!$this->params["request_time"])) {
				$this->params["request_time"]	= LBOX_REQUEST_REQUEST_TIME;
			}
			if (array_key_exists("ref_xtuser", $this->params) && !is_numeric($this->params["ref_xtuser"])) {
				$this->params["ref_xtuser"]	= "NULL";
			}
			$this->params["session_id"]	= session_id();
			// pro jistotu na 2 pokusy
			try {
				parent::store();
			}
			catch (DbControlException $e) {
				try {
					$idColName	= $this->getClassVar("idColName");
					$this->params[$idColName]	= $this->getMaxId()+1;
					parent::store();
				}
				catch (Exception $e) {
					throw $e;
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>