<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2008-07-18
*/
class PageDev extends PageDefault
{
	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
			$record	= new TestRecord();
			$record->store();
			$TAL->records	= new TestRecords();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>