<?php
/**
 * Default component class used in case of no defined component class
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2010-06-23
*/
class WebUI extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}
?>