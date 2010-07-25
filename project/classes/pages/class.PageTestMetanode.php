<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2009-06-06
*/
class PageTestMetanode extends PageDefault
{
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