<?php
/**
 * breadcrumb navigation class
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-03-16
*/
class CounterAcces extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
			$accesViewersRecords 		= new AccesViewersRecords();
			$accesViewersUniqueRecords 	= new AccesViewersUniqueRecords();
			$TAL->count					= $accesViewersRecords->count();
			$TAL->countUnique			= $accesViewersUniqueRecords->count();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>