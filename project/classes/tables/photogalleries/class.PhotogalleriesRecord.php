<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2010-03-08
*/
class PhotogalleriesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "PhotogalleriesRecords";
	public static $tableName    	= "photogalleries";
	public static $idColName    	= "id";

	public static $dependingRecords	= array("");
	
	public static $bounded1M = array("PhotosPhotogalleriesRecords" => "ref_photogallery");
	
	protected static $attributes	=	array(
											array("name"=>"ref_access", "type"=>"int", "notnull" => true, "default"=>"", "visibility"=>"protected"),
											array("name"=>"ref_category", "type"=>"int"),
											array("name"=>"time_published", "type"=>"int", "notnull" => true, "default"=>""),
											array("name"=>"url_cs", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"url_sk", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"url_en", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"name_cs", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"name_sk", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"name_en", "type"=>"shorttext", "notnull" => true, "default"=>""),
											);
		
	public function store() {
		try {
			if (!$this->isInDatabase()) {
				$this->params["ref_access"] = AccesRecord::getInstance()->id;
			}
			if (strlen($this->params["time_published"]) < 1) {
				$this->params["time_published"]	= time();
			}
			if (strlen($this->params["url_cs"]) < 1) {
				$this->params["url_cs"]	= LBoxUtil::getURLByNameString($this->params["name_cs"]);
			}
			if (strlen($this->params["url_sk"]) < 1) {
				$this->params["url_sk"]	= LBoxUtil::getURLByNameString($this->params["name_sk"]);
			}
			if (strlen($this->params["url_en"]) < 1) {
				$this->params["url_en"]	= LBoxUtil::getURLByNameString($this->params["name_en"]);
			}

			if (strlen($this->params["name_sk"]) < 1 && strlen($this->params["name_cs"]) > 0) {
				$this->params["name_sk"]	= $this->params["name_cs"];
			}
			if (strlen($this->params["name_en"]) < 1 && strlen($this->params["name_cs"]) > 0) {
				$this->params["name_en"]	= $this->params["name_cs"];
			}
			if (strlen($this->params["url_sk"]) < 1 && strlen($this->params["url_cs"]) > 0) {
				$this->params["url_sk"]	= $this->params["url_cs"];
			}
			if (strlen($this->params["url_en"]) < 1 && strlen($this->params["url_cs"]) > 0) {
				$this->params["url_en"]	= $this->params["url_cs"];
			}
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * cache var
	 * @var PhotosRecords
	 */
	protected $photos;
	
	/**
	 * pretizeno o mazani fotek
	 */
	public function delete() {
		try {
			foreach ($this->getPhotos() as $photo) {
				$photo->delete();
			}
			@rmdir($this->getPathPhotos());
			parent::delete();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na fotky
	 * @return PhotosRecords
	 */
	public function getPhotos() {
		try {
			if ($this->photos instanceof PhotosRecords) {
				return $this->photos;
			}
			$this->photos	= $this->getBoundedInstance("PhotosPhotogalleriesRecords");
			$this->photos	->setOutputFilterItemsClass("OutputFilterPhotoGalleryImage");
			return $this->photos;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na cestu k fotkam
	 * @return string
	 */
	protected function getPathPhotos() {
		try {
			$pathGallery	= LBoxConfigManagerProperties::gpcn("path_photos_photogalleries");
			$pathGallery	= str_replace("<project>", LBOX_PATH_PROJECT, $pathGallery);
			$pathGallery	= str_replace("<photogallery_name>", $this->get("name"), $pathGallery);
			
			return LBoxUtil::fixPathSlashes($pathGallery);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>