<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox techhouse.cz
* @version 1.0

* @since 2007-12-08
*/
class ArticlesTypesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "ArticlesTypesRecords";
	public static $tableName    	= "articlesTypes";
	public static $idColName    	= "id";
	
	public static $bounded1M = array("ArticlesRecords" => "ref_articleType");
	
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