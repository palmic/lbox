<?php
/**
 * class LBoxExceptionFormValidator
 */
class LBoxExceptionFormValidatorsMaybelline extends LBoxExceptionFormValidator
{
	const CODE_FORM_VALIDATION_CONTROL_MODEL_EMAIL_NOTUNIQUE			= 99001;
	const CODE_FORM_VALIDATION_CONTROL_CITY_NOT_EXISTS					= 99002;
	const CODE_FORM_VALIDATION_CONTROL_REGION_NOT_EXISTS				= 99003;
	const CODE_FORM_VALIDATION_CONTROL_SCHOOL_NOT_EXISTS				= 99004;
	
	const MSG_FORM_VALIDATION_CONTROL_MODEL_EMAIL_NOTUNIQUE				= "competitors email is not unique";
	const MSG_FORM_VALIDATION_CONTROL_CITY_NOT_EXISTS					= "city does not exists";
	const MSG_FORM_VALIDATION_CONTROL_REGION_NOT_EXISTS					= "region does not exists";
	const MSG_FORM_VALIDATION_CONTROL_SCHOOL_NOT_EXISTS					= "region does not exists";
}
?>
