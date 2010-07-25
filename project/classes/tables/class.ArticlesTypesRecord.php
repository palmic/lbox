<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class ArticlesTypesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "ArticlesTypesRecords";
	public static $tableName    	= "articles_types";
	public static $idColName    	= "id";
	
	public static $bounded1M = array("ArticlesRecords" => "ref_articleType");
	
	public static $dependingRecords	= array("");
	
	/**
	 * OutputItem interface method
	 * @throws LBoxException
	 */
	public function __get($name = "") {
		try {
			switch ($name) {
				default:
					return parent::__get($name);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function store() {
		try {
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>