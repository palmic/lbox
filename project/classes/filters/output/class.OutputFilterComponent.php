<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class OutputFilterComponent extends LBoxOutputFilter
{
	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			default:
					return $value;
		}
	}
}
?>