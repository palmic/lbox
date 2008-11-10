<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-24
*/
class ModelsRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "ModelsRecords";
	public static $tableName    	= "models";
	public static $idColName    	= "email";

	public static $boundedM1 		= array("SchoolsRecords" 	=> "ref_school");
	public static $bounded1M 		= array("PhotosRecords" 	=> "ref_model");

	public static $dependingRecords	= array("ModelsPhotosRatingsXTUsersRecords",
											"PhotosRecords", "PhotosRatingsXTUsersRecords", "PhotosRatingsTopRecords", "PhotosRatingsRecords",
											);
		
	/**
	 * cache variables
	 */
	protected $photos;
	protected $school;
	protected $city;
	protected $region;

	public function store() {
		try {
			if (!$this->isInDatabase()) {
				$this->params["created"]	= time();
			}
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na photos
	 * @return PhotosRecords
	 * @throws Exception
	 */
	public function getPhotos() {
		try {
			if ($this->photos instanceof PhotosRecords) {
				return $this->photos;
			}
			return $this->photos	= $this->getBounded1MInstance("PhotosRecords", $filter, $order, $limit, $whereAdd);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na school
	 * @return SchoolsRecord
	 * @throws Exception
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
	 * getter na city
	 * @return CitiesRecord
	 * @throws Exception
	 */
	public function getCity() {
		try {
			if ($this->city instanceof CitiesRecord) {
				return $this->city;
			}
			return $this->city	= $this->getSchool()->getCity();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na region
	 * @return RegionsRecord
	 * @throws Exception
	 */
	public function getRegion() {
		try {
			if ($this->region instanceof RegionsRecord) {
				return $this->region;
			}
			return $this->region	= $this->getSchool()->getCity()->getRegion();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>