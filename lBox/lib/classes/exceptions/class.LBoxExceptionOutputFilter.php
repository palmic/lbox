<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxExceptionOutputFilter extends LBoxExceptionFront
{
	const CODE_RECORD_ISNOT_ABSTRACTRECORDLBOX		= 6001;	
	
	const MSG_RECORD_ISNOT_ABSTRACTRECORDLBOX 		= "defined \$itemType must AbstractRecordLBox type!";
	const MSG_PARAM_COMPONENT_OR_CONFIGITEM 		= "must be component or configitem!";
}
?>