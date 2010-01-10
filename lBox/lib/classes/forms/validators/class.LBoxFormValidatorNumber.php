<?php
/**
 * zkontroluje, jestli je hodnota cislo
 */
class LBoxFormValidatorNumber extends LBoxFormValidator
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > 0)
			if (!is_numeric($control->getValue())) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_NUMBER,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_NUMBER);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>