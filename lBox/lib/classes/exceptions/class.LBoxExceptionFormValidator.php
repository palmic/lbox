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
	const CODE_FORM_VALIDATION_FORM_SUBMITED_YET_CLIENT	= 17008;
	const CODE_FORM_VALIDATION_CONTROL_NOT_NUMBER		= 17009;
	const CODE_FORM_VALIDATION_CONTROL_NOT_VALID		= 17010;
	const CODE_FORM_VALIDATION_FILE_IMAGE_RESOLUTION_HIGH		= 17011;
	const CODE_FORM_VALIDATION_FILE_SIZE_HIGH			= 17012;
	const CODE_FORM_VALIDATION_CONTROL_VALUE_NOT_FREE	= 17013;
	const CODE_FORM_VALIDATION_FILE_NOT_ZIP				= 17014;
	
	const MSG_FORM_VALIDATION_CONTROL_EMPTY				= "required control is empty";
	const MSG_FORM_VALIDATION_PASSWORDS_NOTSAME			= "passwords do not match";
	const MSG_FORM_VALIDATION_EMAIL_NOTVALID			= "email is not valid";
	const MSG_FORM_VALIDATION_SPAMDEFENSEJS_NOT_PASS	= "spam defense does not pass";
	const MSG_FORM_VALIDATION_FILE_NOT_IMAGE			= "file is not image";
	const MSG_FORM_VALIDATION_PHONE_NOTVALID			= "phone is not valid";
	const MSG_FORM_VALIDATION_FILENAME_INVALID			= "filename contents invalid signs";
	const MSG_FORM_VALIDATION_FORM_SUBMITED_YET_CLIENT	= "Form was submited and succesfully processed by this client yet";
	const MSG_FORM_VALIDATION_CONTROL_NOT_NUMBER		= "value is not number";
	const MSG_FORM_VALIDATION_CONTROL_NOT_VALID			= "Invalid value";
	const MSG_FORM_VALIDATION_FILE_IMAGE_RESOLUTION_HIGH			= "Uploaded image exceeded maximum resolution allowed";
	const MSG_FORM_VALIDATION_FILE_SIZE_HIGH			= "Uploaded file exceeded maximum size allowed";
	const MSG_FORM_VALIDATION_CONTROL_VALUE_NOT_FREE	= "Control value already used in target data";
	const MSG_FORM_VALIDATION_FILE_NOT_ZIP				= "file is not zip archive";
}
?>
