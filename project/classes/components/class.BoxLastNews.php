<?php
/**
 * breadcrumb navigation class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-03-12
*/
class BoxLastNews extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
			// DbControl::$debug	= true;
			$itemsLimit	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("box_last_news_limit")->getContent();
			$order["published"]	= 0;
			$limit				= array(0, $itemsLimit);
			$news				= new ArticlesNewsRecords(false, $order, $limit);
			$news4Count			= new ArticlesNewsRecords;
			$news->setOutputFilterItemsClass("OutputFilterArticleNews");
			$TAL->iteratorNews 	= $news;
			$TAL->pageMoreCfg		= LBoxConfigManagerStructure::getInstance()->getPageById(
											LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_articles_news")->getContent()
																							);
			$TAL->atLeastOne	= $news->count() > 0;
			$TAL->hasMore		= $news4Count->count() > $news->count();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>