<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-18
*/
class PageDev extends PageDefault
{
	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
			//$iterator		= new AccesNotViewersRecords(false, false, array(0, 65));
			$iterator		= new LBoxPagingIteratorRecords("AccesNotViewersRecords", 10, false, false, array(0, 65));
var_dump("URL sedmé stránky: ". $iterator->getPages()->getPageByNumber(7)->url);
			$TAL->records	= $iterator;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>