<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2008-03-16
*/
class AccesNotViewersRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "AccesNotViewersRecords";
	public static $tableName    	= "accesViewers";
	public static $idColName    	= "id";

	public static $dependingRecords	= array("");
}
?>