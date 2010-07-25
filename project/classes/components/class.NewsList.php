<?php
/**
 * komponenta zobrazujici seznam novinek
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class NewsList extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		//DbControl::$debug = true;
		try {
			// XT admin
			if (LBoxXT::isLoggedAdmin() && count($_POST[$this->getFormGroupName()]["xt"]) > 0) {
				switch ($_POST[$this->getFormGroupName()]["xt"]["action"]) {
					case "edit":
						$pageEditArticlesId	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_xt_admin_articles")->getContent();
						$pageEditArticles	= LBoxConfigManagerStructure::getPageById($pageEditArticlesId);
						$this->reload($pageEditArticles->url .":". $_POST[$this->getFormGroupName()]["xt"]["id"]);
					break;
					case "delete":
						$this->deleteArticle($_POST[$this->getFormGroupName()]["xt"]["id"]);
						$this->reload();
					break;
				}
			}
			$TAL->xtAdmin		= LBoxXT::isLoggedAdmin();

			$pageMoreId		= LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_articles_news")->getContent();
			$pageMore		= LBoxConfigManagerStructure::getPageById($pageMoreId);
			$limit 			= $this->getListPaging();
			$news 			= new ArticlesNewsRecords(false, array("time_published" => 0), array(1, $limit));
			$news			->setOutputFilterItemsClass("OutputFilterArticleNews");
			$TAL->news 		= ($news->count() > 0) ? $news : false;
			$TAL->urlMore	= $pageMore->url;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Smaze clanek podle predaneho id
	 * @param string $id
	 * @throws Exception
	 */
	protected function deleteArticle($id = "") {
		try {
			if (strlen($id) < 1) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_PARAM_STRING_NOTNULL, LBoxExceptionXT::CODE_BAD_PARAM);
			}
			$record	= new ArticlesRecord($id);
			$record->delete();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci hodnotu paging (po kolika se ma strankovat)
	 * @return int
	 * @throws LBoxException
	 */
	protected function getListPaging() {
		try {
			$out = LBoxConfigManagerProperties::getInstance()
							->getPropertyByName("news_list_limit")
							->getContent();
			if (strlen($out) < 1) {
				$out = LBoxConfigSystem::getInstance()->getParamByPath("output/paging/paging_by_default");
			}
			return (int)$out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>