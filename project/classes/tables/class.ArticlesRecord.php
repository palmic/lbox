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
	 * Photo record type
	 * @var string
	 */
	protected $classNamePhotosRecord		= "PhotosArticlesRecord";
	
	/**
	 * Photo output filter type
	 * @var string
	 */
	protected $classNamePhotosoutputFilter	= "OutputFilterPhotoArticles";
	
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
											array("name"=>"description_cs", "type"=>"longtext", "default"=>""),
											array("name"=>"description_sk", "type"=>"longtext", "default"=>""),
											array("name"=>"time_published", "type"=>"int", "notnull" => true, "default"=>""),
											array("name"=>"ref_photo", "type"=>"int", "notnull" => true),
											array("name"=>"ref_access", "type"=>"int", "notnull" => true, "default"=>""),
											);
	
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
			if (!$this->params["published"]) {
				$this->params["published"] = date("Y-m-d H:i:s");
			}
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
			if (strlen($this->params["ref_photo"]) < 1) {
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