<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox techhouse.cz
* @version 1.0

* @since 2007-12-08
*/
class ArticlesNewsRecords extends ArticlesRecords
{
	public static $itemType = "ArticlesNewsRecord";

	public function __construct($filter = false, $order = false, $limit = false, $whereAdd = "") {
		try {
			$itemType 	= $this->getClassVar("itemType");
			$articlesTypesNewsName	= eval("return $itemType::\$articlesTypesNewsName;");
			$articlesTypes	= new ArticlesTypesRecords(array("name" => $articlesTypesNewsName));
			$filter["ref_articleType"] = $articlesTypes->current()->id;
			parent::__construct($filter, $order, $limit, $whereAdd);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>