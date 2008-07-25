<?php
/**
 * class LBoxExceptionFormValidator
 */
class LBoxExceptionFormValidator extends LBoxExceptionForm
{
	const CODE_FORM_VALIDATION_CONTROL_EMPTY		= 17001;
	const CODE_FORM_VALIDATION_PASSWORDS_NOTSAME	= 17002;
	
	const MSG_FORM_VALIDATION_CONTROL_EMPTY			= "required control is empty";
	const MSG_FORM_VALIDATION_PASSWORDS_NOTSAME		= "passwords do not match";
}
?>
