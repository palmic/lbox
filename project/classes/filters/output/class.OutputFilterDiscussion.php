<?
/**
 * @author Michal Palma <palmic@email.cz>
 * @package LBox
 * @version 1.0

 * @since 2007-12-08
 */
class OutputFilterDiscussion extends OutputFilterComponent
{
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "url":
					if ($this->instance->getParamDirect("type") != "post") {
						return $this->getDiscussionPageUrl();
					}
					else {
						return $this->getDiscussionPageUrl() ."#discussion-post-". $this->instance->getParamDirect("id");
					}
					break;
				case "numPosts":
					return $this->instance->getDescendantsCount();
					break;
				default:
					return $value;
			}
		}
		catch (Exception $e) {
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