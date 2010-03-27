<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2010-03-07
*/
class LBoxExceptionMetaRecords extends LBoxException
{
	const CODE_BAD_DEFINITION_REFERENCE					= 23001;
	const CODE_BAD_DEFINITION_REFERENCE_IMAGE_RESIZE	= 23002;
	const CODE_BAD_DEFINITION_REFERENCE_IMAGE_LIMIT		= 23003;
	const CODE_BAD_DATA_REFERENCE_IMAGE					= 23004;
	
	const MSG_BAD_DEFINITION_REFERENCE					= "Unrecognized reference definition!";
	const MSG_BAD_DEFINITION_REFERENCE_IMAGE_RESIZE		= "Unrecognized reference image resize definition!";
	const MSG_BAD_DEFINITION_REFERENCE_IMAGE_LIMIT		= "Unrecognized reference image limit definition!";
	const MSG_BAD_DATA_REFERENCE_IMAGE					= "Image reference corrupted!";
}
?>