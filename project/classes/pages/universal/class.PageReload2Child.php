<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2010-03-02
*/
class PageReload2Child extends PageDefault
{
	protected function executeStart() {
		try {
			parent::executeStart();
			if ($this->config->getChildNodesIterator()) {
				LBoxFront::reload($this->config->getChildNodesIterator()->current()->url);
			}
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