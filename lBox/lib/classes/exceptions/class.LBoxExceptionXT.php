<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxExceptionXT extends LBoxException
{
	const CODE_LOGIN_INVALID 									= 10001;
	const CODE_NOT_LOGGED 										= 10002;
	const CODE_ROLE_NOT_EXISTS									= 10003;
	const CODE_USER_NOT_CONFIRMED								= 10004;
	const CODE_USER_CONFIRMED									= 10005;
	
	const MSG_LOGIN_INVALID 									= "Login nick or password invalid";	
	const MSG_NOT_LOGGED 										= "User is not signed on!";	
	const MSG_ROLE_NOT_EXISTS									= "XT role does not exists in system!";	
	const MSG_USER_NOT_CONFIRMED								= "User registration not confirmed!";
	const MSG_USER_CONFIRMED									= "User registration already confirmed!";
}
?>