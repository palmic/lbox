<?php
/**
 * pouze pozitivni cislo a nula
 */
class LBoxFormValidatorNumberPositive extends LBoxFormValidatorNumber
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			// puvodni cheking (hodnota = cislo)
			parent::validate($control);
			
			if ($control->getValue() < 1) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_NUMBER_POSITIVE,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_NUMBER_POSITIVE);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>