<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class OutputFilterPagePhotoGallery extends OutputFilterPage
{
	/**
	 * photogallery database record
	 * @var PhotogalleriesRecord
	 */
	protected $photoGallery;

	public function setPhotoGalleryRecord(PhotogalleriesRecord $photoGallery) {
		$this->photoGallery = $photoGallery;
	}
	
	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "title":
				if (!$this->photoGallery instanceof PhotogalleriesRecord) {
					$class = get_class($this);
					throw new LBoxExceptionPage("You have to set photogallery database record via setPhotoGalleryRecord() setter before get values via '$class' instance!");
				}
				$webTitle			= LBoxConfigManagerProperties::getInstance()->getPropertyByName("web_title")->getContent();
				$pageTitlePattern	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("page_title_pattern_photogallery")->getContent();
				$pageTitle			= $pageTitlePattern;
				$pageTitle			= str_replace("\$properties_web_title", $webTitle, 									$pageTitle);
				$pageTitle			= str_replace("\$page_title", 			$value, 									$pageTitle);
				$pageTitle			= str_replace("\$photogallery_name",	$this->photoGallery->name, 					$pageTitle);
				$pageTitle			= trim($pageTitle);
				// v pripade ze mame nakonci samotny oddelovac, odrizneme ho ze stringu
				if (substr($pageTitle, -1) == "|") {
					$pageTitle = trim(substr($pageTitle, 0, strlen($pageTitle)-1));
				}
				return $pageTitle;
				break;
			default:
				return parent::prepare($name, $value);
		}
	}
}
?>