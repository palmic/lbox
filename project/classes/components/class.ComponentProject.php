<?
/**
 * Default component class used in case of no defined component class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class ComponentProject extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
			$TAL->adminLogged	= LBoxXT::isLoggedAdmin();
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}
?>