<?php
/**
 * Default page class used in case of no defined page class
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox
 * @version 1.0
 * @since 2007-12-08
 */
class PageDefault extends PageProject
{
	public function executeInit() {
		try {
			parent::executeInit();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executeStart() {
		try {
			parent::executeStart();
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