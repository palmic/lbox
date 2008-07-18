<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxExceptionConfigComponent extends LBoxExceptionConfig
{
	const CODE_TEMPLATE_NOTFOUND 					= 4001;
	const CODE_CLASS_DEFAULT_NOTFOUND 				= 4004;
	
	const MSG_CLASS_DEFAULT_NOTFOUND 				= "Default class not found in system config!";	
	const MSG_TEMPLATE_NOTFOUND 					= "Component template not found in config!";
}
?>