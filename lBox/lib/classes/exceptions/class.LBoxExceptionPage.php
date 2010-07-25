<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxExceptionPage extends LBoxExceptionComponent
{
	const CODE_DISPLAY_ITEM_NOT_FOUND			= 8001;
	const CODE_PAGING_OUT_OF_LIMIT				= 8002;
	const CODE_CFG_PAGING_BY_DEFAULT_NOT_SET	= 8003;	
	const CODE_DISPLAY_ITEM_FOUND_MORE			= 8004;
	const CODE_URL_PARAM_EMPTY					= 8005;
	
	const MSG_DISPLAY_ITEM_NOT_FOUND 		= "Display item not found by URL param!";	
	const MSG_PAGING_OUT_OF_LIMIT			= "Paging shifted out of limit!";
	const MSG_CFG_PAGING_BY_DEFAULT_NOT_SET	= "Default paging_by_default value not set, or is not numeric. Please set it in system config!";	
	const MSG_DISPLAY_ITEM_FOUND_MORE 		= "More display items found by URL param!";	
	const MSG_URL_PARAM_EMPTY		 		= "URL param empty!";	
}
?>