<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-05-03
*/
class BannersAccesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "BannersAccesRecords";
	public static $tableName    	= "banners_acces";
	public static $idColName    	= "id";

	public static $boundedM1 = array(
									"BannersRecords" 	=> "ref_banners",
									"AccesRecords" 		=> "ref_access",
									);
	
	public static $dependingRecords	= array(
											"BannersDisplayedRecords",
											"BannersRecords",
	);
									
	/**
	 * getter for banner
	 * @return BannersRecord
	 * @throws Exception
	 */
	public function getBanner() {
		try {
			$records	= $this->getBoundedM1Instance("BannersRecords", $filter, $order, $limit, $whereAdd);
			if (!$records->current() instanceof AbstractRecord) {
				throw new Exception("Theres no bounded banner record in database!");
			}
			return $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter for acces
	 * @return AccesRecord
	 * @throws Exception
	 */
	public function getAccess() {
		try {
			$records	= $this->getBoundedM1Instance("AccesRecords", $filter, $order, $limit, $whereAdd);
			if (!$records->current() instanceof AbstractRecord) {
				throw new Exception("Theres no bounded access record in database!");
			}
			return $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>