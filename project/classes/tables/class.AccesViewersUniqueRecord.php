<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2008-03-16
*/
class AccesViewersUniqueRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "AccesViewersUniqueRecords";
	public static $tableName    	= "accesViewersUnique";
	public static $idColName    	= "ip";

	public static $dependingRecords	= array("");
}
?>