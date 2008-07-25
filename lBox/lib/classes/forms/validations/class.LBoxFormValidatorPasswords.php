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
			if (reset($controls)->getValue() != end($controls)->getValue()) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_PASSWORDS_NOTSAME,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_PASSWORDS_NOTSAME);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>
