<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-24
*/
class PhotosRatingsRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "PhotosRatingsRecords";
	public static $tableName    	= "photos_ratings";
	public static $idColName    	= "id";

	public static $boundedM1 = array(	"PhotosRecords" => "ref_photo",
										"AccesRecords" 	=> "ref_acces");
	
	public static $dependingRecords	= array("PhotosRatingsXTUsersRecords", "PhotosRatingsTopRecords",
											"ModelsPhotosRatingsXTUsersRecords",
	);
	
	/**
	 * cache variables
	 */
	protected $photo;
	protected $acces;
	protected $userXT;
	
	/**
	 * getter na photo
	 * @return PhotosRecord
	 * @throws Exception
	 */
	public function getPhoto() {
		try {
			if ($this->photo instanceof PhotosRecord) {
				return $this->photo;
			}
			return $this->photo	= $this->getBoundedM1Instance("PhotosRecords", $filter, $order, $limit, $whereAdd)->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na acces
	 * @return AccesRecord
	 * @throws Exception
	 */
	public function getAcces() {
		try {
			if ($this->acces instanceof AccesRecord) {
				return $this->acces;
			}
			return $this->acces	= $this->getBoundedM1Instance("AccesRecords", $filter, $order, $limit, $whereAdd)->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na hlasujiciho usera
	 * @return XTUsersRecord
	 * @throws Exception
	 */
	public function getXTUser() {
		try {
			if ($this->userXT instanceof XTUsersRecord) {
				return $this->userXT;
			}
			return $this->userXT	= new XTUsersRecord($this->getAcces()->ref_xtUser);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>