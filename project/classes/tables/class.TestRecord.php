<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox techhouse.cz
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-09-07
*/
class TestRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "TestRecords";
	public static $tableName    	= "test";
	public static $idColName    	= "id";

	public static $dependingRecords	= array("");
}
?>