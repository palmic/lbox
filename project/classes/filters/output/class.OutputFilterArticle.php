<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class OutputFilterArticle extends LBoxOutputFilter
{
	/**
	 * name of config attribute referencing article displaying page via ID
	 * @var string
	 */
	protected $configVarNameArticleRefPage 	= "ref_page_article";

	/**
	 * article ref page class
	 * @var string
	 */
	protected $articleRefPageClass 			= "PageArticle";
	
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "id":
					return $this->instance->getParamDirect("url");
					break;
				case "url":
					$refPageAttName				= $this->configVarNameArticleRefPage;
					// najdeme stranku zobrazovani clanku podle reference
					$idPageItem					= LBoxConfigManagerProperties::getInstance()->getPropertyByName($refPageAttName)->getContent();
					$pageItem		 			= LBoxConfigManagerStructure::getInstance()->getPageById($idPageItem);
					$pageClass 					= $pageItem->class;
					if ($pageClass !== $this->articleRefPageClass) {
						throw new LBoxExceptionConfigStructure("Referenced page (id=$idPagePhotogallery) defined in properties.xml like '$refPageAttName' is not '". $this->articleRefPageClass ."' type, but '$pageClass'! Check it in structure config.");
					}
					$urlBase	= $this->instance->getParamDirect("url");
					return $pageItem->url .":$urlBase";
					break;
				case "urlAbsolute":
					return LBOX_REQUEST_URL_SCHEME ."://". LBOX_REQUEST_URL_HOST . $this->prepare("url");
					break;
				case "publishedDate":
					return date("j.n. Y", LBoxUtil::getDateTimeStamp($this->instance->published));
					break;
				default:
					return $value;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>