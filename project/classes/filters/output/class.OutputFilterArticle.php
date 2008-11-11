<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox softub.cz
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
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

	/**
	 * cache funkce
	 * @var bool
	 */
	protected $isLastHeadlight;

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
				case "headingEntities":
					return htmlentities($this->prepare("heading"));
					break;
				case "publishedDate":
					return date("j.n. Y", LBoxUtil::getDateTimeStamp($this->instance->published));
					break;
				case "published2":
					return date("j.n.Y | H:i:s", LBoxUtil::getDateTimeStamp($this->instance->published));
					break;
				case "isLastHeadlight":
					return $this->isLastHeadlight();
					break;
				case "listIcon":
					return $this->getListIconURL();
					break;
				case "listIconDimensions":
					return array(
								"x"	=> LBoxConfigManagerProperties::getPropertyContentByName("article_image_title_width"),
								"y"	=> LBoxConfigManagerProperties::getPropertyContentByName("article_image_title_height"),
								);
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
	 * vraci, jestli je clanek posledni headlight
	 * @return bool
	 */
	protected function isLastHeadlight() {
		try {
			if (is_bool($this->isLastHeadlight)) {
				return $this->isLastHeadlight;
			}
			$className				= $this->instance->getClassVar("itemsType");
			$articlesHeadlight 		= new $className(false, array("headlight" => 0, "published" => 0), array(0, 1));
			return $this->isLastHeadlight = ($this->instance->getParamDirect("url") == $articlesHeadlight->current()->url);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci url na ikonu pokud nejaka u clanku je
	 * @return string
	 */
	protected function getListIconURL() {
		try {
			// custom icon
			if ($this->instance->getParamDirect("ref_list_icon") < 1) {
				if ($this->instance->getImageTitle()) {
					return $this->instance->getImageTitle()->url;
				}
			}
			$urlPath	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("icon_url_path")->getContent();
			if ($this->instance->getParamDirect("ref_list_icon") < 1) {
				$records	= new ListIconsRecords(false, array("default" => 0, "id" => 0), array(0, 1));
				$record		= $records->current();
			}
			else {
				$record		= new ListIconsRecord($this->instance->getParamDirect("ref_list_icon"));
			}
			return "$urlPath/". $record->filename .".". $record->ext;
		}
		catch (Exception $e) {
			switch ($e->getCode()) {
				case LBoxExceptionFilesystem::CODE_FILE_NOT_EXISTS:
						return "image not found";
					break;
				default:
					throw $e;
			}
		}
	}
}
?>