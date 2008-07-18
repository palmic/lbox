<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-02-13
*/
class LBoxExceptionCache extends LBoxException
{
	const CODE_CACHE_CANNOT_WRITE			= 12001;
	const CODE_CACHE_CANNOT_READ			= 12002;
	const CODE_CACHE_CANNOT_OPEN_WRITE		= 12003;
	const CODE_CACHE_CANNOT_OPEN_READ		= 12004;
	
	const MSG_CACHE_CANNOT_WRITE			= "Cannot write cache on filesystem!";
	const MSG_CACHE_CANNOT_READ				= "Cannot read cache from filesystem!";
	const MSG_CACHE_CANNOT_OPEN_WRITE		= "Cannot open cache file for write!";
	const MSG_CACHE_CANNOT_OPEN_READ		= "Cannot open cache file for read!";
}
?>