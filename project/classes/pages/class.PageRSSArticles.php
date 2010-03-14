<?php
/**
 * @author Michal Palma <palmic@email.cz>
 * @package LBox techhouse.cz
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @since 2007-12-15
 */
class PageRSSArticles extends PageRSS
{
	protected $articlesRecordsClassName				=  "ArticlesRecords";
	protected $articlesRecordsOutputFilterClassName	=  "OutputFilterArticleRSS";
	protected $itemsLimitConfigParamName			=  "rss_articles_num";
	
	protected function executePrepend(PHPTAL $TAL) {
		//DbControl::$debug = true;
		try {
			$itemsLimit			= LBoxConfigManagerProperties::getInstance()->getPropertyByName($this->itemsLimitConfigParamName)->getContent();
			$className			= $this->articlesRecordsClassName;
			$articles 			= new $className(false, array("time_published" => 0), array(0, $itemsLimit));
			$articles			->setOutputFilterItemsClass($this->articlesRecordsOutputFilterClassName);
			$TAL->items 		= $articles;
			$TAL->host			= LBOX_REQUEST_URL_SCHEME ."://". LBOX_REQUEST_URL_HOST;
		}
		catch (Exception $e) {
			throw $e;
		}
	}	
}
?>