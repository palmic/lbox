<?php
/**
 * class LBoxExceptionFormValidator
 */
class LBoxExceptionFormValidator extends LBoxExceptionForm
{
	const CODE_FORM_VALIDATION_CONTROL_EMPTY			= 17001;
	const CODE_FORM_VALIDATION_PASSWORDS_NOTSAME		= 17002;
	const CODE_FORM_VALIDATION_EMAIL_NOTVALID			= 17003;
	const CODE_FORM_VALIDATION_SPAMDEFENSEJS_NOT_PASS	= 17004;
	const CODE_FORM_VALIDATION_FILE_NOT_IMAGE			= 17005;
	const CODE_FORM_VALIDATION_PHONE_NOTVALID			= 17006;
	const CODE_FORM_VALIDATION_FILENAME_INVALID			= 17007;
	const CODE_FORM_VALIDATION_CONTROL_VALUE_NOT_VALID	= 17008;
	
	const MSG_FORM_VALIDATION_CONTROL_EMPTY				= "required control is empty";
	const MSG_FORM_VALIDATION_PASSWORDS_NOTSAME			= "passwords do not match";
	const MSG_FORM_VALIDATION_EMAIL_NOTVALID			= "email is not valid";
	const MSG_FORM_VALIDATION_SPAMDEFENSEJS_NOT_PASS	= "spam defense does not pass";
	const MSG_FORM_VALIDATION_FILE_NOT_IMAGE			= "file is not image";
	const MSG_FORM_VALIDATION_PHONE_NOTVALID			= "phone is not valid";
	const MSG_FORM_VALIDATION_FILENAME_INVALID			= "filename contents invalid signs";
	const MSG_FORM_VALIDATION_CONTROL_VALUE_NOT_VALID	= "Invalid value";
}
?>
