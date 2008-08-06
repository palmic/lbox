<?
/**
 * Default page class used in case of no defined page class
 * @author Michal Palma <palmic@email.cz>
 * @package LBox
 * @version 1.0
 * @since 2007-12-08
 */
class PageDefault extends PageMaybelline
{
	protected function executeStart() {
		try {
			$this->config->setOutputFilter(new OutputFilterPage($this->config));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executePrepend(PHPTAL $TAL) {
		//DbControl::$debug = true;
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>