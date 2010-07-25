<?
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-12-15
*/
class OutputFilterPageRSS extends OutputFilterPage
{
	protected $configParamNameWebTitle	= "web_title";
	protected $configParamNamePageTitle	= "page_rss_title_pattern";
	protected $articleHeading			= "";

	public function prepare($name = "", $value = NULL) {
		
		$discussionURLParams	= $this->instance->getDiscussionURLParamsArray();
		// zvolime spravny nazev konfiguracniho parametru pro titulek - podle toho co zobrazujeme za RSS
		switch ($discussionURLParams[0]) {
			// clanek
			case LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_article")->getContent():
					$articles	= new ArticlesRecords(array("url" => $discussionURLParams[1]));
					if ($articles->count() < 1) LBoxFront::reloadHomePage();
					else $this->articleHeading	= $articles->current()->heading;
					$this->configParamNamePageTitle = "page_rss_discussion_article_title_pattern";
				break;
			// party
			/*
			case LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_party")->getContent():
					$parties	= new PartiesRecords(array("url" => $discussionURLParams[1]));
					if ($parties->count() < 1) LBoxFront::reloadHomePage();
					else $this->articleHeading	= $parties->current()->name ." ". $parties->current()->datetime;
					$this->configParamNamePageTitle = "page_rss_discussion_party_title_pattern";
				break;
			*/
			// guestbook
			case LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_guestbook")->getContent():
				$this->configParamNamePageTitle = "page_rss_discussion_guestbook_title_pattern";
				break;
		}
		return parent::prepare($name, $value);
	}
}
?>