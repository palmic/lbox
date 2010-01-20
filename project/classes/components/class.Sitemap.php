<?php
/**
 * breadcrumb navigation class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-03-16
*/
class Sitemap extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
			$pagesIterator	= LBoxConfigManagerStructure::getInstance()->getIterator();
			$pagesIterator->setOutputFilterItemsClass("OutputFilterPage");
			$TAL->structureIterator 	= $pagesIterator;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>