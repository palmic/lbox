<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox techhouse.cz
* @version 1.0
* @since 2007-12-08
*/
class XTUsersRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "XTUsersRecords";
	public static $tableName    	= "xtUsers";
	public static $idColName    	= "id";
	public static $passwordColNames	= array("password");

	public static $boundedM1 = array("XTRolesRecords" => "ref_xtRole");
	public static $bounded1M = array("RegistrationConfirmsRecords" => "ref_xtUser");
	
	protected	$hashString	= "sdfsdf";
	
	public static $dependingRecords	= array("");
	
	/**
	 * OutputItem interface method
	 * @throws LBoxException
	 */
	public function __get($name = "") {
		try {
			switch ($name) {
				default:
					return parent::__get($name);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function store() {
		try {
			if (!$this->params["created"]) {
				$this->params["created"] = date("Y-m-d H:i:s");
			}
			if (!$this->params["ref_xtRole"]) {
				$this->params["ref_xtRole"] = LBoxXT::getRoleRecordByName(LBoxXT::XT_ROLE_NAME_USER)->id;
			}
			if (!$this->isInDatabase()) {
				$this->params["hash"]	= md5($this->get("nick") . $this->hashString . $this->get("password"));
			}
			parent::store();
			// vygenerovani hashe
			if (strlen(trim($this->params["hash"])) < 1) {
				$hashString	= "kiou5s" . (string)rand(1, 9999) . $this->params[self::$idColName];
				$this->__set("hash", md5((string)$hashString));
			}
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na registration confirm
	 * @return RegistrationConfirmsRecord
	 * @throws Exception
	 */
	public function getRegistrationConfirm() {
		try {
			return $this->getBoundedM1Instance("RegistrationConfirmsRecords", $filter, $order, $limit, $whereAdd)->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na XT roli
	 * @return XTRolesRecords
	 * @throws Exception
	 */
	public function getRole() {
		try {
			return $this->getBoundedM1Instance("XTRolesRecords", $filter, $order, $limit, $whereAdd)->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>