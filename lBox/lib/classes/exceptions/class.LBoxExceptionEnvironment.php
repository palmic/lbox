<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
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