<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-06-06
*/
class PageTest extends PageRecordsList
{
	protected $classNameRecord				= "ArticlesNewsRecord";
	protected $classNameRecordOutputFilter	= "OutputFilterArticleNews";
	protected $propertyNamePagingPageRange 	= "";
	protected $propertyNamePagingBy 		= "";
	protected $propertyNameRefPageEdit		= "ref_page_xt_edit_article_news";
	protected $orderBy						= array("time_published" => 0, "ref_access" => 0);
	
	protected function executePrepend(PHPTAL $TAL) {
		// DbControl::$debug = true;
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na Form noveho metarecordu
	 * @return LBoxForm
	 */
	public function getFormNewRecord() {
		try {
			return LBoxMetaRecordsManager::getForm($this->classNameRecord);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na $classNameRecord
	 * @return string
	 */
	public function getClassNameRecord() {
		try {
			return $this->classNameRecord;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>