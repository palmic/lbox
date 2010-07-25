<?php
/**
 * Default component class used in case of no defined component class
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class ComponentDefault extends ComponentProject
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
			parent::executePrepend($TAL);
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}
?>