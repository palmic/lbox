<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-05-03
*/
class BannersRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "BannersRecords";
	public static $tableName    	= "banners";
	public static $idColName    	= "filename";
	
	public static $bounded1M = array(
									"BannersAccesRecords" 	=> "ref_banners",
									);
	
	public static $dependingRecords	= array(
											"BannersDisplayedRecords",
											"BannersAccesRecords",
	);
									
	public function store() {
		try {
			if (!$this->isInDatabase()) {
				$this->params["created"] = date("Y-m-d H:i:s");
			}
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * loggs show
	 * @throws Exception
	 */
	public function logShow() {
		try {
			$bannerAccess				= new BannersAccesRecord();
			$bannerAccess->ref_banner	= $this->get("filename");
			$bannerAccess->ref_access	= AccesRecord::getInstance()->id;
			$bannerAccess->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns banner extension
	 * @return string
	 */
	public function getExtension() {
		try {
			$filenameParts	= explode(".", $this->get("filename"));
			return ($filenameParts[count($filenameParts)-1]);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter for bounded BannersAccesRecords
	 * @return BannersAccesRecords
	 * @throws Exception
	 */
	public function getAccess() {
		try {
			return	$this->getBounded1MInstance("BannersAccesRecords");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>