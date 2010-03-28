<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2010-03-08
*/
class PhotogalleriesCategoriesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "PhotogalleriesCategoriesRecords";
	public static $tableName    	= "photogalleries_categories";
	public static $idColName    	= "id";

	public static $dependingRecords	= array("");
	
	/**
	 * cache var
	 * @var PhotogalleriesRecords
	 */
	protected $photogalleries;
	
	protected static $attributes	=	array(
											array("name"=>"url_cs", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"url_sk", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"url_en", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"name_cs", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"name_sk", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"name_en", "type"=>"shorttext", "notnull" => true, "default"=>""),
											);
		
	public function store() {
		try {
			if (strlen($this->params["url_cs"]) < 1) {
				$this->params["url_cs"]	= LBoxUtil::getURLByNameString($this->params["name_cs"]);
			}
			if (strlen($this->params["url_sk"]) < 1) {
				$this->params["url_sk"]	= LBoxUtil::getURLByNameString($this->params["name_sk"]);
			}
			if (strlen($this->params["url_en"]) < 1) {
				$this->params["url_en"]	= LBoxUtil::getURLByNameString($this->params["name_en"]);
			}
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na fotogalerie
	 * @reutrn PhotogalleriesRecords
	 */
	public function getPhotogalleries() {
		try {
			if ($this->photogalleries instanceof PhotogalleriesRecords) {
				return $this->photogalleries;
			}
			$this->photogalleries	= new PhotogalleriesRecords(array("ref_category" => $this->get($this->getClassVar("idColName"))));
			$this->photogalleries	->setOutputFilterItemsClass("OutputFilterPhotoGallery");
			return $this->photogalleries;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>