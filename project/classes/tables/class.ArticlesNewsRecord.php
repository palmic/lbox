<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class ArticlesNewsRecord extends ArticlesRecord
{
	/**
	 * Nazev typu clanku - novinky
	 * @var string
	 */
	public static $articlesTypesNewsName	= "news";
	
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
			$articlesTypesNewsName	= $this->getClassVar("articlesTypesNewsName");
			$articlesTypes			= new ArticlesTypesRecords(array("name" => $articlesTypesNewsName));
			$this->params["ref_type"] 	= $articlesTypes->current()->id;
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}	
}
?>