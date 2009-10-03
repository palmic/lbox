<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-24
*/
class AdminLogin extends PageDefault
{
	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
			if (LBoxXT::isLoggedAdmin()) {
				$this->reloadAdminHome();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * reloadne hlavni stranku adminu
	 */
	protected function reloadAdminHome() {
		try {
			if (strlen($adminHomePageID	= LBoxConfigManagerProperties::getPropertyContentByName("ref_page_xt_admin")) < 1) {
				throw new LBoxExceptionPage("Property ref_page_xt_admin not set!");
			}
			LBoxFront::reload(LBoxConfigManagerStructure::getPageById($adminHomePageID)->url);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>