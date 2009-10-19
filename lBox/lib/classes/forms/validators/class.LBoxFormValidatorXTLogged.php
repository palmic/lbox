<?php
/**
 * zkontroluje, jestli uzivatel prihlasen - bez ohledu na hodnotu prvku
 */
class LBoxFormValidatorXTLogged extends LBoxFormValidator
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (!LBoxXTProject::isLogged()) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionXT::MSG_NOT_LOGGED,
														LBoxExceptionXT::CODE_NOT_LOGGED);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>