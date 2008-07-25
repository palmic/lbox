<?php
/**
 * class LBoxFormValidatorRequired
 */
class LBoxFormValidatorRequired extends LBoxFormValidator
{
	/**
	 * kontroluje, jestli byl control uzivatelem zadan
	 */
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) < 1) {
				throw new LBoxExceptionFormValidator($control->getName() .": ". LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_EMPTY, LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_EMPTY);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>
