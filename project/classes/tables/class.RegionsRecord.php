<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-24
*/
class RegionsRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "RegionsRecords";
	public static $tableName    	= "regions";
	public static $idColName    	= "id";

	public static $bounded1M = array("CitiesRecords" => "ref_region");

	public static $dependingRecords	= array("SchoolsCitiesRegionsRecords", "TopRatedPhotosBySchoolsRecords",
											"CitiesRecords",
											"SchoolsRecords", "SchoolsLastCreatedRecords", "SchoolsCitiesRegionsRecords",
											"ModelsRecords", "ModelsPhotosRatingsXTUsersRecords",
											"PhotosRecords", "PhotosRatingsXTUsersRecords", "PhotosRatingsTopRecords", "PhotosRatingsRecords",
	);
}
?>