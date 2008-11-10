<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-26
*/
class ProductsRegistrationRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "ProductsRegistrationRecords";
	public static $tableName    	= "products_registration";
	public static $idColName    	= "id";

	public static $boundedM1 		= array("SchoolsRecords" 	=> "ref_school");
	public static $bounded1M 		= array("PhotosRecords" 	=> "ref_model");
	
	public static $dependingRecords	= array("ProductsRegistrationXTUsersRecords", "ProductsRegistrationXXTUsersRecords");
	
	/**
	 * cache variables
	 */
	protected $photos;

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
}
?>