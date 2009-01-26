<?
/**
 * @author Michal Palma <palmic@email.cz>
 * @package LBox
 * @version 1.0

 * @since 2007-12-15
 */
class OutputFilterDiscussionComponent extends OutputFilterComponent
{
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "rssURL":
					$rssPageId	= $this->instance->rss;
					$rssPageUrl	= LBoxConfigManagerStructure::getInstance()->getPageById($rssPageId)->url;
					$pageId		= $this->instance->page->id;
					return "$rssPageUrl:$pageId/". LBoxFront::getLocationUrlParam();
					break;
				default:
					return $value;
			}
		}
		catch (Exception $e) {
			if ($e->getCode() == LBoxExceptionConfig::CODE_NODE_BYID_NOT_FOUND) {
				throw new LBoxExceptionConfig("Discussion component 'rss' ". LBoxExceptionConfig::MSG_ATTRIBUTE_WRONG, LBoxExceptionConfig::CODE_ATTRIBUTE_WRONG);
			}
			throw $e;
		}
	}

	/**
	 * vraci URL stranky/clanku/galerie, atd..  ke ktere je diskuze pripojena
	 * @return string
	 */
	protected function getDiscussionPageUrl() {
		try {
			$pageUrl	= LBoxConfigManagerStructure::getInstance()->getPageById($this->instance->pageId)->url;
			$paramUrl 	= $this->instance->urlParam;
			return $pageUrl . (strlen($paramUrl) > 0 ? ":$paramUrl" : "");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>