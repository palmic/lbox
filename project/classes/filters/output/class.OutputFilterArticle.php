<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox softub.cz
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2010-02-12
*/
class OutputFilterArticle extends OutputFilterRecordEditableByAdmin
{
	/**
	 * name of config attribute referencing article displaying page via ID
	 * @var string
	 */
	protected $configVarNameArticleRefPage 	= "ref_page_article";

	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "url":
					$pageItem = LBoxConfigManagerStructure::getInstance()->getPageById(LBoxConfigManagerProperties::gpcn($this->configVarNameArticleRefPage));
					return  LBoxUtil::getURLWithParams(array($this->instance->getParamDirect("url")), $pageItem->url);
					break;
				case "url_param":
					return  $this->instance->getParamDirect("url");
					break;
				case "urlAbsolute":
					return LBOX_REQUEST_URL_SCHEME ."://". LBOX_REQUEST_URL_HOST . $this->prepare("url");
					break;
				case "headingEntities":
					return htmlentities($this->prepare("heading"));
					break;
				case "publishedDate":
					return date("j.n. Y", $this->instance->time_published);
					break;
				case "published2":
					return date("j.n.Y | H:i:s", $this->instance->time_published);
					break;
				case "perex_raw":
					return strip_tags($this->instance->perex);
					break;
				default:
					return parent::prepare($name, $value);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>