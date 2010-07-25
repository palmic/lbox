<?
/**
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox
 * @version 1.0

 * @since 2007-12-08
 */
class OutputFilterPagePhotogalleries extends OutputFilterPage
{
	protected $propertyNameRefRSSPage	= "ref_page_rss_photogalleries";
	
	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "rssURL":
				$rssPageId	= LBoxConfigManagerProperties::getInstance()->getPropertyByName($this->propertyNameRefRSSPage)->getContent();
				$rssPageUrl	= LBoxConfigManagerStructure::getInstance()->getPageById($rssPageId)->url;
				return $rssPageUrl;
				break;
			default:
				return parent::prepare($name, $value);
		}
	}
}
?>