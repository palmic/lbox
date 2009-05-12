<?php
require_once("class.LBoxException.php");
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxExceptionLoader extends LBoxException
{
	const CODE_INVALID_DIRPATH 			= 101;
	const CODE_TYPE_NOT_FOUND 			= 1000;
	const CODE_TYPE_LOAD_ERROR 			= 1001;
	
	const MSG_INVALID_DIRPATH 		= "must be valid absolute valid path to dir!";	
	const MSG_TYPE_NOT_FOUND 	= "type not found!";	
	const MSG_TYPE_LOAD_ERROR 	= "type was found, but cannot be loaded!";	
}
?>