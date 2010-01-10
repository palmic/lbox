<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2008-04-13
*/
class LBoxExceptionEnvironment extends LBoxException
{
	const CODE_PHP_EXTENSION_NOTEXISTS			= 13001;
	
	const MSG_PHP_EXTENSION_NOTEXISTS			= "Required PHP extension did not found!";
}
?>