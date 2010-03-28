<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2010-03-28
*/
class LBoxExceptionClasses extends LBoxException
{
	const CODE_TARGET_CLASS_EXISTS					= 24001;
	const CODE_SOURCE_CLASS_NOT_EXISTS				= 24002;
	
	const MSG_TARGET_CLASS_EXISTS					= "Target class already exists!";
	const MSG_SOURCE_CLASS_NOT_EXISTS				= "Source class does not exists!";
}
?>