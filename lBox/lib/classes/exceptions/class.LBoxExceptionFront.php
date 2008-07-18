<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxExceptionFront extends LBoxException
{
	const CODE_PAGE_NOT_FOUND 			= 2001;
	const CODE_PAGE_BAD_TYPE 			= 2002;
	const CODE_COMPONENT_BAD_TYPE		= 2003;
	const CODE_SLOT_NOT_DEFINED			= 2004;	
	const CODE_SLOT_DEFINED				= 2005;	
	const CODE_TPL_BAD_KEY				= 2006;	
	const CODE_TPL_COMPONENT_NAME_EMPTY	= 2007;	
	const CODE_TPL_LBOX_NS_BAD_CALL		= 2008;	
	const CODE_PAGE404_NOT_DEFINED		= 2009;	
	const CODE_PAGE404_NOT_FOUND		= 2010;	
	const CODE_HTTP_STATUS_NOT_FOUND	= 2011;	
	
	const CODE_ACCES_MULTIPLE_INSTANCES	= 2011;	
	
	const MSG_PAGE404_NOT_DEFINED		= "Page 404 not found in system config!";	
	const MSG_PAGE404_NOT_FOUND 		= "Page 404 not found in structure config in project!";	
	const MSG_PAGE_BAD_TYPE 			= "defined page class must be \"page\" type!";	
	const MSG_COMPONENT_BAD_TYPE		= "defined component class must be Component type!";	
	const MSG_SLOT_NOT_DEFINED			= "slot is not defined!";	
	const MSG_SLOT_DEFINED				= "slot is already defined!";	

	const MSG_TPL_SLOT_NAME_NOT_VALID	= "slot name invalid!";	
	const MSG_TPL_SLOT_NAME_EMPTY		= "slot name empty!";	
	const MSG_TPL_COMPONENT_NAME_EMPTY	= "slot name empty!";	
	const MSG_TPL_LBOX_NS_BAD_CALL		= "invalid call called on namespace lbox!";	

	const MSG_ACCES_MULTIPLE_INSTANCES	= "Cannot create more, than one AccesRecord instances!";	
	const MSG_HTTP_STATUS_NOT_FOUND		= "Given HTTP status not found in definition!";
}
?>