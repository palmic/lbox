<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox techhouse.cz
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2007-12-08
*/
class DiscussionsPostsRecords extends DiscussionsRecords
{
    public static $itemType = "DiscussionsPostsRecord";
	
    public function __construct($filter = false, $order = false, $limit = false, $whereAdd = NULL) {
    	$filter["type"] = "post";
    	parent::__construct($filter, $order, $limit, $whereAdd);
    }
}
?>