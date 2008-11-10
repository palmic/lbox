<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-24
*/
class CitiesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "CitiesRecords";
	public static $tableName    	= "cities";
	public static $idColName    	= "id";

	public static $boundedM1 = array("RegionsRecords" => "ref_region");
	public static $bounded1M = array("SchoolsRecords" => "ref_city");

	public static $dependingRecords	= array("SchoolsCitiesRegionsRecords", "TopRatedPhotosBySchoolsRecords",
											"SchoolsRecords", "SchoolsLastCreatedRecords", "SchoolsCitiesRegionsRecords",
											"ModelsRecords", "ModelsPhotosRatingsXTUsersRecords",
											"PhotosRecords", "PhotosRatingsXTUsersRecords", "PhotosRatingsTopRecords", "PhotosRatingsRecords",
	);
	
	/**
	 * cache variables
	 */
	protected $region;
	protected $schools;
	
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
			return $this->region	= $this->getBoundedM1Instance("RegionsRecords", $filter, $order, $limit, $whereAdd)->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na schools
	 * @return SchoolsRecords
	 * @throws Exception
	 */
	public function getSchools() {
		try {
			if ($this->schools instanceof SchoolsRecords) {
				return $this->schools;
			}
			return $this->schools	= $this->getBounded1MInstance("SchoolsRecords", $filter, $order, $limit, $whereAdd);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>