<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxExceptionConfigStructure extends LBoxExceptionConfig
{
	const CODE_TEMPLATE_DEFAULT_NOTFOUND 			= 3001;
	const CODE_CLASS_DEFAULT_NOTFOUND 				= 3004;
	const CODE_NODE_BYURL_NOT_FOUND					= 3005;
	const CODE_NEEDED_OUTPUTFILTER_NOT_DEFINED		= 3006;
	
	const MSG_TEMPLATE_DEFAULT_NOTFOUND 			= "Default template not found in system config!";
	const MSG_CLASS_DEFAULT_NOTFOUND 				= "Default class not found in system config!";
	
	const MSG_PARAM_NOT_NODENAME					= "must be page element!";
	const MSG_NODE_BYURL_NOT_FOUND					= "node was not found in config by given url attribute!";	
	const MSG_NEEDED_OUTPUTFILTER_NOT_DEFINED		= "this ConfigItem needs output filter that is not defined!";
}
?>