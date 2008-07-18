<?php
/**
 * class LBoxExceptionFormControl
 */
class LBoxExceptionFormControl extends LBoxExceptionForm
{
	const CODE_FORM_FILTER_OUT_VALUE_NULL		= 16001;
	const CODE_FORM_CONTROL_VALUE_NOT_OPTION	= 16002;
	
	const MSG_FORM_FILTER_OUT_VALUE_NULL		= "form validator output value cannot be NULL";
	const MSG_FORM_CONTROL_VALUE_NOT_OPTION		= "form control value sent is not in options";
}
?>
