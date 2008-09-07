<?php
/**
 * class LBoxExceptionFormControl
 */
class LBoxExceptionFormControl extends LBoxExceptionForm
{
	const CODE_FORM_FILTER_OUT_VALUE_NULL		= 16001;
	const CODE_FORM_CONTROL_VALUE_NOT_OPTION	= 16002;
	const CODE_FORM_CONTROL_VALUE_SET_ALREADY	= 16003;
	const CODE_FORM_CONTROL_FILE_UPLOAD_ERROR	= 16101;
	const CODE_FORM_CONTROL_FORM_NOT_SET		= 16102;
	
	const MSG_FORM_FILTER_OUT_VALUE_NULL		= "form validator output value cannot be NULL";
	const MSG_FORM_CONTROL_VALUE_NOT_OPTION		= "form control value sent is not in options";
	const MSG_FORM_CONTROL_VALUE_SET_ALREADY	= "form control value already set in option";
	const MSG_FORM_CONTROL_FILE_UPLOAD_ERROR	= "posible form upload attack detected!";
	const MSG_FORM_CONTROL_FORM_NOT_SET			= "you have to set form instance via setter before!";
}
?>
