<?php
/**
 * Default page class used in case of no defined page class
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class PageSitemap09 extends LBoxPage
{
	/**
	 * pridava default vlastnosti sablonam
	 * @param PHPTAL $TAL
	 * @throws Exception
	 */
	protected function executePrepend(PHPTAL $TAL) {
		try {
			$pagesIterator	= LBoxConfigManagerStructure::getInstance()->getIterator();
			$pagesIterator->setOutputFilterItemsClass("OutputFilterPage");
			$TAL->structureIterator 	= $pagesIterator;
			$TAL->host					= LBOX_REQUEST_URL_SCHEME ."://". LBOX_REQUEST_URL_HOST;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executeStart() {
		try {
			parent::executeStart();
			header('Content-Type: application/xml');
		}
		catch (Exception $e) {
			throw $e;
		}
	}	
}
?>