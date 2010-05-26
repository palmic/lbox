<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-06-06
*/
class PageTestMetarecord extends PageRecordsList
{
	protected $classNameRecord				= "ArticlesNewsRecord";
	protected $classNameRecordOutputFilter	= "OutputFilterArticleNews";
	protected $propertyNamePagingPageRange 	= "";
	protected $propertyNamePagingBy 		= "";
	protected $propertyNameRefPageEdit		= "ref_page_xt_edit_article_news";
	protected $orderBy						= array("time_published" => 0, "ref_access" => 0);
	
	protected function executePrepend(PHPTAL $TAL) {
		//DbControl::$debug = true;
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>