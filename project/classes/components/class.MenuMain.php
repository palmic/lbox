<?
/**
 * main menu class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class MenuMain extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
			$pagesIterator	= LBoxConfigManagerStructure::getInstance()->getIterator();
			$pagesIterator->setOutputFilterItemsClass("OutputFilterPage");
			$TAL->pages 	= $pagesIterator;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>