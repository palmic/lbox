<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class AccesRecords extends AbstractRecordsLBox
{
    public static $itemType = "AccesRecord";

	/**
	 * do not use cache
	 */
	public function isCacheOn() {return false;}
}
?>