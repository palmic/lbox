<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2009-05-16
*/
class DataContactFormRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "DataContactFormRecords";
	public static $tableName    	= "data_contact_form";
	public static $idColName    	= "id";

	public static $dependingRecords	= array(
											"",
	
	);
}
?>