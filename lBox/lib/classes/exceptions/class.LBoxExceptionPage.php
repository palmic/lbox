<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxExceptionPage extends LBoxExceptionComponent
{
	const CODE_DISPLAY_ITEM_NOT_FOUND			= 8001;
	const CODE_PAGING_OUT_OF_LIMIT				= 8002;
	const CODE_CFG_PAGING_BY_DEFAULT_NOT_SET	= 8003;	
	
	const MSG_DISPLAY_ITEM_NOT_FOUND 		= "Display item not found by URL param!";	
	const MSG_PAGING_OUT_OF_LIMIT			= "Paging shifted out of limit!";
	const MSG_CFG_PAGING_BY_DEFAULT_NOT_SET	= "Default paging_by_default value not set, or is not numeric. Please set it in system config!";	
}
?>