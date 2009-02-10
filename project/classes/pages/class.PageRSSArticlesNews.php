<?
/**
 * @author Michal Palma <palmic@email.cz>
 * @package LBox techhouse.cz
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @since 2007-12-15
 */
class PageRSSArticlesNews extends PageRSSArticles
{
	protected $articlesRecordsClassName				=  "ArticlesNewsRecords";
	protected $articlesRecordsOutputFilterClassName	=  "OutputFilterArticleNewsRSS";
	protected $itemsLimitConfigParamName			=  "rss_articles_num";
}
?>