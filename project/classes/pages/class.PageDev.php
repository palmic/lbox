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
DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);

			$TAL->records	= new TestRecords();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>