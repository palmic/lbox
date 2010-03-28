<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class OutputFilterPhotoGalleryImage extends LBoxOutputFilter
{
	/**
	 * @var PhotogalleriesImagesRecord
	 */
	protected $instance;
	
	public function prepare($name = "", $value = NULL) {
		try {
			$virtPath	= LBoxConfigSystem::getInstance()->getParamByPath("photogallery/output/path_virtual");
			switch ($name) {
				case "url":
					$value 	= str_replace(LBOX_PATH_PROJECT, "", $this->getPath());
					break;
				case "name":
					if (strlen($value) < 1) {
						$value = $this->instance->filename; 
					}
					break;
				case "thumbnail":
					$class = get_class($this->instance);
					if (($thumb = $this->instance->getChildren()->current()) instanceof $class) {
						$myClassName = get_class($this);
						$thumb->setOutputFilter(new $myClassName($thumb));
						$value = $thumb;
					}
					else {
						$value = NULL;
					}
					break;
				default:
			}
			return $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function getPath() {
		try {
			$pathGallery	= LBoxConfigManagerProperties::gpcn("path_photos_photogalleries");
			$pathGallery	= str_replace("<project>", LBOX_PATH_PROJECT, $pathGallery);
			$pathGallery	= str_replace("<photogallery_name>", $this->getPhotogallery()->name, $pathGallery);
			$pathGallery	= str_replace("<photogallery_url>", $this->getPhotogallery()->getParamDirect("url"), $pathGallery);
			
			$fileName		= $this->instance->getParamDirect("filename");
			$ext			= $this->instance->getParamDirect("ext");
			return LBoxUtil::fixPathSlashes("$pathGallery/$fileName.$ext");
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * cache var
	 * @var array
	 */
	protected static $pathsPhotogalleries	= array();
	
	/**
	 * getter na cestu k fotkam fotogallerie podle fotky
	 * @param PhotosRecord $photo
	 */
	protected function getPhotogallery() {
		try {
			$phGID	= $this->instance->getParamDirect("ref_photogallery");
			if (strlen(self::$pathsPhotogalleries[$phGID]) > 0) {
				return self::$pathsPhotogalleries[$phGID];
			}
			self::$pathsPhotogalleries[$phGID]	= new PhotogalleriesRecord($phGID);
			self::$pathsPhotogalleries[$phGID]	->setOutputFilter(new OutputFilterPhotoGallery(self::$pathsPhotogalleries[$phGID]));
			return self::$pathsPhotogalleries[$phGID];
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>