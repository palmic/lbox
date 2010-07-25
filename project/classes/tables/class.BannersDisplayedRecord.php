<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-05-03
*/
class BannersDisplayedRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "BannersDisplayedRecords";
	public static $tableName    	= "bannersDisplayed";
	public static $idColName    	= "filename";

	public static $boundedM1 = array(
									"BannersRecords" 	=> "filename",
									);

	public static $dependingRecords	= array(
											"BannersRecords",
											"BannersAccesRecords",
	);
									
	public function getExtension() {
		try {
			return $this->getBanner()->getExtension();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter for banner
	 * @return BannersRecord
	 * @throws Exception
	 */
	public function getBanner() {
		try {
			return $this->getBoundedM1Instance("BannersRecords")->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>