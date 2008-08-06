<?
/**
 * submenu class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2008-02-02
*/
class MenuSub extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
			if ($pagesIterator	= $this->page->config->getChildNodesIterator()) {				
				$pagesIterator->setOutputFilterItemsClass("OutputFilterPage");
				$TAL->pages 	= $pagesIterator;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>