<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-24
*/
class ModelsPhotosRatingsXTUsersRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "ModelsPhotosRatingsXTUsersRecords";
	public static $tableName    	= "models_photos_ratings_xt_users";
	public static $idColName    	= "ref_photo";

	public static $boundedM1 = array(	"PhotosRecords" 	=> "ref_photo",
										"SchoolsRecords" 	=> "ref_school",
										"CitiesRecords" 	=> "ref_city",
	);
	
	/**
	 * cache variables
	 */
	protected $photo;
	protected $school;
	protected $city;
	protected $xtUserVotedFor;
	
	/**
	 * vraci instanci soutezici fotky
	 * @return PhotosRecord
	 */
	public function getPhoto() {
		try {
			if ($this->photo instanceof PhotosRecord) {
				return $this->photo;
			}
			$this->photo	= $this->getBoundedM1Instance("PhotosRecords", $filter, $order, $limit, $whereAdd)->current();
			$this->photo->setOutputFilter(new OutputFilterPhoto($this->photo));
			return $this->photo;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci skolu, ke ktere patri toto foto
	 * @return SchoolsRecord
	 */
	public function getSchool() {
		try {
			if ($this->school instanceof SchoolsRecord) {
				return $this->school;
			}
			return $this->school	= $this->getBoundedM1Instance("SchoolsRecords", $filter, $order, $limit, $whereAdd)->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci mesto, ke kteremu patri toto foto
	 * @return CitiesRecord
	 */
	public function getCity() {
		try {
			if ($this->city instanceof CitiesRecord) {
				return $this->city;
			}
			return $this->city	= $this->getBoundedM1Instance("CitiesRecords", $filter, $order, $limit, $whereAdd)->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci, jestli pro dane foto modelky current XTUser jiz hlasoval
	 * @return bool
	 * @throws LBoxException
	 */
	public function didUserXTVotedFor() {
		try {
			if (is_bool($this->xtUserVotedFor)) {
				return $this->xtUserVotedFor;
			}
			if (!LBoxXT::isLogged()) return false;
			$records	= new PhotosRatingsXTUsersRecords(array("id_photo" => $this->get("ref_photo"), "email" => LBoxXT::getUserXTRecord()->email));
			return ($records->count() > 0);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>