<?php
class LBoxFormValidatorPasswords extends LBoxFormValidator
{
	/**
	 * kontroluje, jestli se hesla shoduji
	 */
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$controls	= $control->getControls();
			// shodnost hesel
			if (first($controls)->getValue() != end($controls)->getValue()) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_PASSWORDS_NOTSAME,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_PASSWORDS_NOTSAME);
			}
			// pokryjeme moznost zapomenuti nastaveni "required" u jednotlivych controls
			if (strlen(current($controls)->getValue()) < 1) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_EMPTY,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_EMPTY);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>
