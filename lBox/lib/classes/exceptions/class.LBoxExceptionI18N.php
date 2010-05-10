<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2008-08-15
*/
class LBoxExceptionI18N extends LBoxException
{
	const CODE_LNG_NOTEXISTS					= 19001;
	const CODE_LNG_ITEM_NOTEXISTS				= 19002;
	
	const MSG_LNG_NOTEXISTS						= "This language mutation does not exists";
	const MSG_LNG_ITEM_NOTEXISTS				= "Text item with this id does not exists in this language mutation";

	protected $logVerbose	= true;
}
?>