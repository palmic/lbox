<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2010-03-02
*/
class PageReload2Child extends PageDefault
{
	public function executeInit() {
		try {
			parent::executeInit();
			if ($this->config->getChildNodesIterator()) {
				LBoxFront::reload($this->config->getChildNodesIterator()->current()->url);
			}
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
			parent::executePrepend($TAL);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>