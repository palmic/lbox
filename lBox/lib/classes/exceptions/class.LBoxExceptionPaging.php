<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2009-08-17
*/
class LBoxExceptionPaging extends LBoxException
{
	const CODE_INSTANCE_ID_INVALID					= 21001;

	const MSG_INSTANCE_ID_INVALID					= "Paging instance ID does not correspont with this instance, probably defined before by other instance!";	
}
?>