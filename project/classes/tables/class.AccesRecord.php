<?php
/**
 * Pozor! pouziva se jako singleton presto ze ma public constructor - kuli dedicnosti
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class AccesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "AccesRecords";
	public static $tableName    	= "acces";
	public static $idColName    	= "id";

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
	 * Pozor! pouziva se jako singleton presto ze ma public constructor - kuli dedicnosti
	 */
	public function __construct() {
		$className	= __CLASS__;
		if (AccesRecord::$instance instanceof $className) {
			//throw new LBoxExceptionFront(LBoxExceptionFront::MSG_ACCES_MULTIPLE_INSTANCES, LBoxExceptionFront::CODE_ACCES_MULTIPLE_INSTANCES);
		}
	}

	/**
	 * @return AccesRecord
	 * @throws Exception
	 */
	public static function getInstance() {
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
	
	/**
	 * autosave - pohodlnejsi nez nutne volat store
	 * @throws Exception
	 */
	public function __destruct() {
		try {
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
			$this->params["time"] 				= date("Y-m-d H:i:s");
			$this->params["ip"] 				= LBOX_REQUEST_IP;
			$this->params["url"] 				= LBOX_REQUEST_URL;
			$this->params["referer"] 			= LBOX_REQUEST_REFERER;
			$this->params["agent"] 				= LBOX_REQUEST_AGENT;
			if (!$this->params["request_time"]) {
				$this->params["request_time"]	= LBOX_REQUEST_REQUEST_TIME;
			}
			if (LBoxXT::isLogged()) {
				$this->params["ref_xtUser"]		= LBoxXT::getUserXTRecord()->id;
			}
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