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
	public static $idColName    	= "id";

	/**
	 * Photo record type
	 * @var string
	 */
	protected $classNamePhotosRecord		= "PhotosArticlesRecord";
	
	/**
	 * Photo output filter type
	 * @var string
	 */
	protected $classNamePhotosoutputFilter	= "OutputFilterPhotoArticles";
	
	public static $boundedM1 = array(
									"ArticlesTypesRecords" 	=> "ref_type",
									);
	
	protected static $attributes	=	array(
											array("name"=>"ref_photo", "type"=>"int", "notnull" => true,
													"reference" => array("type" => "PhotosArticlesRecords", "of" => "OutputFilterPhotoArticles", "label" => "filename",
														"size_resize" => array("x" => 300, "y" => 300, "proportions" => 1),
														"size_limit" => array("longer" => 300))),
											array("name"=>"ref_type", "type"=>"int", "notnull" => true, "default"=>"1", "required" => true, "visibility"=>"public",
													"reference" => array("type" => "ArticlesTypesRecords", "label" => "name")),
											array("name"=>"url_cs", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"url_sk", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"heading_cs", "type"=>"shorttext", "notnull" => true, "default"=>"", "required" => true),
											array("name"=>"heading_sk", "type"=>"shorttext", "notnull" => true, "default"=>"", "required" => true),
											array("name"=>"perex_cs", "type"=>"richtext", "default"=>"", "required" => true),
											array("name"=>"perex_sk", "type"=>"richtext", "default"=>"", "required" => true),
											array("name"=>"body_cs", "type"=>"richtext", "default"=>"", "required" => true),
											array("name"=>"body_sk", "type"=>"richtext", "default"=>"", "required" => true),
											array("name"=>"description_cs", "type"=>"longtext", "default"=>""),
											array("name"=>"description_sk", "type"=>"longtext", "default"=>""),
											array("name"=>"time_published", "type"=>"int", "notnull" => true, "default"=>""),
											array("name"=>"ref_access", "type"=>"int", "notnull" => true, "default"=>"", "visibility"=>"protected"),
											);
	
	public static $dependingRecords	= array("");
	
	/**
	 * cache var
	 * @var PhotosRecord
	 */										
	protected $photo;
	
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
			if (!$this->params["time_published"] || strtolower($this->params["time_published"]) == "<<null>>") {
				$this->params["time_published"] = time();
			}
			if (strlen($this->params["url_cs"]) < 1 || $this->params["url_cs"] == "<<NULL>>") {
				$this->params["url_cs"] = LBoxUtil::getURLByNameString($this->params["heading_cs"]);
			}
			if (strlen($this->params["url_sk"]) < 1 || $this->params["url_sk"] == "<<NULL>>") {
				$this->params["url_sk"] = LBoxUtil::getURLByNameString($this->params["heading_sk"]);
			}
			$this->params["ref_access"] = AccesRecord::getInstance()->id;
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizeno o mazani titulni fotky
	 */
	public function delete() {
		try {
			$this->deletePhoto();
			parent::delete();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na photo clanku
	 * @return PhotosRecord
	 */
	public function getPhoto() {
		try {
			if ($this->photo instanceof PhotosRecord) {
				return $this->photo;
			}
			if (!$this->params["ref_photo"]) {
				return NULL;
			}
			$classNamePhotosRecord	= $this->classNamePhotosRecord;
			$classNamePhotosRecords	= eval("return $classNamePhotosRecord::\$itemsType;");
			$idColName				= eval("return $classNamePhotosRecord::\$idColName;");
			$records				= new $classNamePhotosRecords(array($idColName => $this->params["ref_photo"]));
			$records				->setOutputFilterItemsClass($this->classNamePhotosoutputFilter);
			return $this->photo		= $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * smaze photo clanku
	 */
	public function deletePhoto() {
		try {
			if ($photo = $this->getPhoto()) {
				$this->__set("ref_photo", "NULL");
				$this->store();
				$photo->delete();
				$this->photo	= NULL;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>