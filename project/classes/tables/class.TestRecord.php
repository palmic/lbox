<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
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

	protected static $attributes	=	array(
											array("name"=>"ref_type", "type"=>"int", "notnull" => true, "default"=>"", "visibility"=>"protected"),
											array("name"=>"url_cs", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"url_sk", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"heading_cs", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"heading_sk", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"perex_cs", "type"=>"richtext", "default"=>""),
											array("name"=>"perex_sk", "type"=>"richtext", "default"=>""),
											array("name"=>"body_cs", "type"=>"richtext", "default"=>""),
											array("name"=>"body_sk", "type"=>"richtext", "default"=>""),
											array("name"=>"time_published", "type"=>"int", "notnull" => true, "default"=>""),
											array("name"=>"ref_access", "type"=>"int", "notnull" => true, "default"=>""),
											);
}
?>