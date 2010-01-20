<?
/**
 * @author Michal Palma <palmic@email.cz>
 * @package LBox
 * @version 1.0

 * @since 2007-12-08
 */
class OutputFilterPageDownload extends OutputFilterPage
{
	protected $propertyNameRefRSSPage	= "ref_page_rss_download";
	
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