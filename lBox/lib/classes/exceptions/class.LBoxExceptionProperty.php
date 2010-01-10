<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2007-12-08
*/
class LBoxExceptionProperty extends LBoxException
{
	const CODE_PROPERTY_NOT_FOUND 				= 7001;
	const CODE_PROPERTY_LANGDOMAIN_NOT_FOUND 	= 7002;
	
	const MSG_PROPERTY_NOT_FOUND 				= "property not found!";	
	const MSG_PROPERTY_LANGDOMAIN_NOT_FOUND 	= "langdomain not found!";	
}
?>