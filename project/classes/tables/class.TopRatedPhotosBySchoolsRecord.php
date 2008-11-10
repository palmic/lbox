<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-29
*/
class TopRatedPhotosBySchoolsRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "TopRatedPhotosBySchoolsRecords";
	public static $tableName    	= "top_rated_photos_by_schools";
	public static $idColName    	= "id";
	
	public static $dependingRecords	= array("");
}
?>