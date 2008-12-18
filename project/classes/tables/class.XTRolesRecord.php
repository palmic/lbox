<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox techhouse.cz
* @version 1.0

* @since 2007-12-08
*/
class XTRolesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "XTRolesRecords";
	public static $tableName    	= "xtRoles";
	public static $idColName    	= "id";

	public static $bounded1M = array("XTUsersRecords" => "ref_xtRole");
	
	public static $dependingRecords	= array("XTUsersRecords");
	
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
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na users role
	 * @return XTUsersRecords
	 * @throws Exception
	 */
	public function getUsers() {
		try {
			return $this->getBounded1MInstance("XTUsersRecords", $filter, $order, $limit, $whereAdd);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>