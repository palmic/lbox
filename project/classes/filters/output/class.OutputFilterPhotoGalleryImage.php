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
					$urlGallery	= $this->instance->getGallery()->getParamDirect("url");
					$fileName	= $this->instance->getFileName();
					$url 		= "$virtPath/$urlGallery/$fileName";
					$value 		= $url;
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
}
?>