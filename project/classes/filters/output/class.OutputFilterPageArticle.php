<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class OutputFilterPageArticle extends OutputFilterPage
{
	/**
	 * article database record
	 * @var ArticlesRecord
	 */
	protected $article;

	public function setArticleRecord(ArticlesRecord $article) {
		$this->article = $article;
	}

	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "title":
				if (!$this->article instanceof ArticlesRecord) {
					$class = get_class($this);
					throw new LBoxExceptionPage("You have to set article database record via setArticleRecord() setter before get values via '$class' instance!");
				}
				$webTitle			= LBoxConfigManagerProperties::getInstance()->getPropertyByName("web_title")->getContent();
				$pageTitlePattern	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("page_title_pattern_article")->getContent();
				$pageTitle			= $pageTitlePattern;
				$pageTitle			= str_replace("\$properties_web_title", $webTitle, 					$pageTitle);
				$pageTitle			= str_replace("\$page_title", 			$value, 					$pageTitle);
				$pageTitle			= str_replace("\$article_name",			$this->article->heading, 	$pageTitle);
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