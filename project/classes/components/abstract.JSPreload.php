<?php
/**
 * main menu class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-06-08
*/
abstract class JSPreload extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * prepinac na zobrazovani webadminu
	 * @return bool
	 */
	public function isToShow() {
		try {
			if (LBoxXTDBFree::isLogged()) {
				return true;
			}
			else {
				return LBoxXTProject::isLoggedAdmin();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>