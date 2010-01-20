<?php
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
			$TAL->empty	= false;
			if ($pagesIterator	= $this->page->config->getChildNodesIterator()) {				
				$pagesIterator->setOutputFilterItemsClass("OutputFilterPage");
				$TAL->pages 	= $pagesIterator;
			}
			else {
				$TAL->empty	= true;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>