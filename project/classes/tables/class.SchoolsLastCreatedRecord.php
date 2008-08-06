<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-01
*/
class SchoolsLastCreatedRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "SchoolsLastCreatedRecords";
	public static $tableName    	= "schools_last_created";
	public static $idColName    	= "ref_school";
}
?>