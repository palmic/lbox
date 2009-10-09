<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2009-10-08
*/
class PageAdminListInquiries extends PageRecordsList
{
	protected $classNameRecord				= "InquiriesRecord";
	protected $classNameRecordOutputFilter	= "OutputFilterInquiry";
	protected $propertyNamePagingPageRange 	= "";
	protected $propertyNamePagingBy 		= "";
	protected $propertyNameRefPageEdit		= "ref_page_xt_edit_inquiry";
	protected $orderBy						= array("id" => 0);
	
	protected function executePrepend(PHPTAL $TAL) {
		// DbControl::$debug = true;
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>