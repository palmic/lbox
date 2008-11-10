<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox techhouse.cz
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-07-31
*/
class PhotosRatingsTopRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "PhotosRatingsTopRecords";
	public static $tableName    	= "photos_ratings_top";
	public static $idColName    	= "id";

	public static $boundedM1 = array("PhotosRecords" => "id");
	
	public static $dependingRecords	= array("");
	
	/**
	 * cache variables
	 */
	protected $photo;
	protected $school;
	
	/**
	 * getter na photo pro pripad, ze bysme potrebovali READ/WRITE model, nebo nektere jeho specificke gettery
	 * @return PhotosRecord
	 * @throws Exception
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
	 * Vraci relevantni skolu soutezici s timto fotem
	 * @return SchoolsRecord
	 * @throws LBoxException
	 */
	public function getSchool() {
		try {
			if ($this->school instanceof SchoolsRecord) {
				return $this->school;
			}
			$records	= new SchoolsRecords(array("id" => $this->get("ref_school")));
			if ($records->count() < 1) {
				throw new LBoxException("School not found by id - system error!");
			}
			$this->school	= $records->current();
			$this->school->setOutputFilter(new OutputFilterSchool($this->school));
			return $this->school;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>