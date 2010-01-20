<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class ArticlesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "ArticlesRecords";
	public static $tableName    	= "articles";
	public static $idColName    	= "url";

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
			if (!$this->params["published"]) {
				$this->params["published"] = date("Y-m-d H:i:s");
			}
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>