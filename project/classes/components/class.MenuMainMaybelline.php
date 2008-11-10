<?
/**
 * main menu class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-29
*/
class MenuMainMaybelline extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
			$pagesIterator	= LBoxConfigManagerStructure::getInstance()->getIterator();
			$pagesIterator->setOutputFilterItemsClass("OutputFilterPageMaybelline");
			$TAL->pages 	= $pagesIterator;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>