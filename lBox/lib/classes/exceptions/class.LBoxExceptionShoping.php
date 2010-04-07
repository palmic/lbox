<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2009-10-10
*/
class LBoxExceptionShoping extends LBoxException
{
	const CODE_CART_EMPTY 							= 22001;
	const CODE_ORDER_ALREADY_DEFINED 				= 22002;
	const CODE_ITEM_AMOUNT_NOT_AVAILABLE_IN_STORE	= 22003;
	
	const MSG_CART_EMPTY							= "Shoping cart is empty!";	
	const MSG_ORDER_ALREADY_DEFINED 				= "Order already defined!";
	const MSG_ITEM_AMOUNT_NOT_AVAILABLE_IN_STORE	= "Item is not available in this amount in store!";
}
?>