<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-09-07
*/
class InquiriesOptionsResponsesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "InquiriesOptionsResponsesRecords";
	public static $tableName    	= "inquiries_options_responses";
	public static $idColName    	= "ref_response";
	public static $dependingRecords	= array("InquiriesOptionsRecords", "InquiriesResponsesRecords");
}
?>