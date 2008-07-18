<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxExceptionConfig extends LBoxException
{
	const CODE_TYPE_NOT_FOUND 									= 1000;
	const CODE_TYPE_LOAD_ERROR 									= 1001;
	const CODE_CLASS_NOT_ITERATOR_CONFIG 						= 1002;
	const CODE_CLASS_NOT_CONFIG 								= 1003;
	const CODE_ABSTRACT_CLASSNAME_NOT_DEFINED 					= 1004;
	const CODE_ABSTRACT_NODENAME_NOT_DEFINED 					= 1005;
	const CODE_CLASS_NOT_CONFIG_ITEM 							= 1006;
	const CODE_ATTRIBUTE_NOT_DEFINED 							= 1007;
	const CODE_NODE_CONTENT_ISNOT_STRING						= 1008;	
	const CODE_ATTRIBUTE_UNIQUE_EMPTY							= 1009;
	const CODE_ATTRIBUTE_UNIQUE_NOT_UNIQUE						= 1010;
	const CODE_NODE_BYID_NOT_FOUND								= 1011;
	const CODE_PAGING_URLPARAM_EXAMPLE_NOT_CORRESPOND_PATTERN	= 1012;
	const CODE_CFG_FILE_NOT_DEFINED								= 1013;
	const CODE_INVALID_PATH 									= 1014;
	
	const MSG_CFG_FILE_NOT_DEFINED 									= "must be valid absolute valid path to dir!";	
	const MSG_INVALID_PATH 											= "path is invalid. Config file or config paths error!";	
	const MSG_TYPE_NOT_FOUND 										= "type not found!";	
	const MSG_TYPE_LOAD_ERROR 										= "type was found, but cannot be loaded!";	

	const MSG_PARAM_NOT_NODENAME									= "must be nodeName element (defined in class where i was thrown)!";
	const MSG_CLASS_NOT_ITERATOR_CONFIG								= "must be LBoxIteratorConfig type (defined in class where i was thrown)!";
	const MSG_CLASS_NOT_CONFIG										= "must be LBoxConfig type (defined in class where i was thrown)!";
	const MSG_CLASS_NOT_CONFIG_ITEM									= "must be LBoxConfigItem type (defined in class where i was thrown)!";
	
	const MSG_ABSTRACT_CLASSNAME_NOT_DEFINED						= "abstract param className is not defined (defined in class where i was thrown)!";
	const MSG_ABSTRACT_NODENAME_NOT_DEFINED							= "abstract param nodeName is not defined (defined in class where i was thrown)!";
	
	const MSG_ATTRIBUTE_NOT_DEFINED 								= "config attribute is not defined!";	

	const MSG_NODE_CONTENT_ISNOT_STRING 							= "config node content is not string!";	
	const MSG_ATTRIBUTE_UNIQUE_EMPTY								= "unique attribute is empty or not defined!";
	const MSG_ATTRIBUTE_UNIQUE_NOT_UNIQUE							= "unique attribute is not uniue!";
	const MSG_NODE_BYID_NOT_FOUND									= "node was not found in config by given id attribute!";	
	const MSG_PAGING_URLPARAM_EXAMPLE_NOT_CORRESPOND_PATTERN		= "paging_url_param_example config value does not correspond paging_url_param_pattern value!";	
}
?>