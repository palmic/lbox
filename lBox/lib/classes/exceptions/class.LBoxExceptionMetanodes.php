<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2009-06-06
*/
class LBoxExceptionMetanodes extends LBoxExceptionCache
{
	const CODE_DATA_CANNOT_WRITE			= 20001;
	const CODE_DATA_CANNOT_READ				= 20002;
	const CODE_DATA_CANNOT_OPEN_WRITE		= 20003;
	const CODE_DATA_CANNOT_OPEN_READ		= 20004;
	const CODE_NODETYPE_UNRECOGNIZED		= 20005;

	const CODE_NODECONTENT_NOT_NUMERIC		= 20006;
	const CODE_NODE_ALREADY_EXISTS_ANOTHER_TYPE		= 20007;
	
	
	const MSG_DATA_CANNOT_WRITE				= "Cannot write metanode data on filesystem!";
	const MSG_DATA_CANNOT_READ				= "Cannot read metanode data from filesystem!";
	const MSG_DATA_CANNOT_OPEN_WRITE		= "Cannot open metanode data file for write!";
	const MSG_DATA_CANNOT_OPEN_READ			= "Cannot open metanode data file for read!";
	const MSG_NODETYPE_UNRECOGNIZED 		= "LBoxMetanode type is unrecognized!";
	const MSG_NODECONTENT_NOT_NUMERIC 		= "Metanode content is not numeric!";
	const MSG_NODE_ALREADY_EXISTS_ANOTHER_TYPE 		= "Wanted metanode does alerady exists, but have another type!";
}
?>