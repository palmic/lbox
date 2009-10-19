<?php
/**
 * kontroluje hodnotu, jestli je integer
 */
class LBoxFormValidatorInteger extends LBoxFormValidator
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > 0)
			if (!is_int($control->getValue())) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_INTEGER,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_INTEGER);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>