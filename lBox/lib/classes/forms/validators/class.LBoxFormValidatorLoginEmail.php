<?php
/**
 * validator kontrolujici login pomoci emailu a hesla (nepouziva nick)
 *
 */
class LBoxFormValidatorLoginEmail extends LBoxFormValidator
{
	/**
	 * kontroluje, jestli se hesla shoduji
	 */
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$records	= new XTUsersRecords(array(
													"email" 	=> $control->getControlByName("email")->getValue(),
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