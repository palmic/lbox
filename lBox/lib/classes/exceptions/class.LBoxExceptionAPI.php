<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox techhouse.cz
* @version 1.0

* @date 2007-12-09
*/
class LBoxExceptionAPI extends LBoxException
{
	const CODE_ITEM_NOTEXISTS					= 11001;
	const CODE_BAD_RATING_TYPE					= 11002;
	const CODE_BAD_ID							= 11003;
	
	const MSG_ITEM_NOTEXISTS					= "Item with this id does not exists!";
	const MSG_BAD_RATING_TYPE					= "Bad rating type given!";
	const MSG_BAD_ID							= "Given ID was not found!";
}
?>