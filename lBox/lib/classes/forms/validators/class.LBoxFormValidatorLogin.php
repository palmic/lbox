<?php
/**
 * validator kontrolujici login pomoci nicku a hesla
 *
 */
class LBoxFormValidatorLogin extends LBoxFormValidator
{
	/**
	 * kontroluje, jestli se hesla shoduji
	 */
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$records	= new XTUsersRecords(array(
													"nick" 	=> $control->getControlByName("nick")->getValue(),
													"password" 	=> $control->getControlByName("password")->getValue(),
			));
			if ($records->count() < 1) {
				throw new LBoxExceptionFormValidatorsLogin(	LBoxExceptionFormValidatorsLogin::MSG_FORM_VALIDATION_LOGIN_NOTSUCCES,
															LBoxExceptionFormValidatorsLogin::CODE_FORM_VALIDATION_LOGIN_NOTSUCCES);
			}
			if ($records->current()->confirmed < 1) {
				throw new LBoxExceptionFormValidatorsLogin(	LBoxExceptionFormValidatorsLogin::MSG_FORM_VALIDATION_LOGIN_NOTCONFIRMED,
															LBoxExceptionFormValidatorsLogin::CODE_FORM_VALIDATION_LOGIN_NOTCONFIRMED);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>