<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @date 2009-08-17
*/
class LBoxExceptionPaging extends LBoxException
{
	const CODE_INSTANCE_ID_INVALID					= 21001;
	const CODE_URL_PARAM_INVALID_PATTERN			= 21002;
	const CODE_URL_PARAM_INVALID					= 21003;
	const CODE_PAGE_OUT_OF_RANGE					= 21004;
	
	const MSG_INSTANCE_ID_INVALID					= "Paging instance ID does not correspont with this instance, probably defined before by other instance!";	
	const MSG_URL_PARAM_INVALID_PATTERN				= "URL param found by getter does not correspond URL param pattern!";	
	const MSG_URL_PARAM_INVALID						= "URL param does not correspond with anticipated pattern!";	
	const MSG_PAGE_OUT_OF_RANGE						= "Page out of range!";	
}
?>