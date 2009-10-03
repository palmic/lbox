<?php
/**
 * validator kontrolujici login pomoci nicku a hesla pro login bez databaze
 *
 */
class LBoxFormValidatorLoginDBFree extends LBoxFormValidator
{
	/**
	 * kontroluje, jestli se hesla shoduji
	 */
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$flagNameFound	= false;
			foreach (LBoxConfigManagerAuthDBFree::getInstance()->getLoginsByPassword($control->getControlByName("password")->getValue()) as $login) {
				if ($login->name == $control->getControlByName("nick")->getValue()) {
					$flagNameFound	= true;
				}
			}
			if (!$flagNameFound) {
				throw new LBoxExceptionFormValidatorsLogin(	LBoxExceptionFormValidatorsLogin::MSG_FORM_VALIDATION_LOGIN_NOTSUCCES,
															LBoxExceptionFormValidatorsLogin::CODE_FORM_VALIDATION_LOGIN_NOTSUCCES);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>