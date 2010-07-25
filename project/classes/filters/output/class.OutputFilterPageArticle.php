<?
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2010-02-18
*/
class OutputFilterPageArticle extends OutputFilterPage
{
	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "title":
				if (!LBoxFront::getPage()->getRecord() instanceof ArticlesRecord) {
					$class = get_class($this);
					throw new LBoxExceptionPage("Wrong data returns by LBoxFront::getPage()->getRecord(): ". get_class(LBoxFront::getPage()->getRecord()) ."!");
				}
				$webTitle			= LBoxConfigManagerProperties::getInstance()->getPropertyByName("web_title")->getContent();
				$pageTitlePattern	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("page_title_pattern_article")->getContent();
				$pageTitle			= $pageTitlePattern;
				$pageTitle			= str_replace("\$properties_web_title", $webTitle, 					$pageTitle);
				$pageTitle			= str_replace("\$page_title", 			$value, 					$pageTitle);
				$pageTitle			= str_replace("\$record_name",			LBoxFront::getPage()->getRecord()->heading, 	$pageTitle);
				$pageTitle			= str_replace("\$article_name", 		LBoxFront::getPage()->getRecord()->heading, 	$pageTitle);
				$pageTitle			= trim($pageTitle);
				// v pripade ze mame nakonci samotny oddelovac, odrizneme ho ze stringu
				if (substr($pageTitle, -1) == "|") {
					$pageTitle = trim(substr($pageTitle, 0, strlen($pageTitle)-1));
				}
				return $pageTitle;
				break;
			case "description":
				return (string)LBoxFront::getPage()->getRecord()->getParamDirect($name);
			break;
			case "heading":
				return LBoxFront::getPage()->getRecord()->getParamDirect($name);
			break;
			case "nameBreadcrumb":
			case "headingBreadcrumb":
					return $this->instance->heading;
				break;
			default:
				return parent::prepare($name, $value);
		}
	}
}
?>