<?php
class ValidatorLoginParalel extends LBoxFormValidatorEmail
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getForm()->getControlByName("nick")->getValue()) > 0
			&&	strlen($control->getForm()->getControlByName("password")->getValue()) > 0) {
				if (LBoxXTProject::isLoggedParalellyByLogin($control->getForm()->getControlByName("nick")->getValue(), $control->getForm()->getControlByName("password")->getValue())) {
					throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidatorsLogin::MSG_FORM_VALIDATION_LOGIN_PARALEL,
															LBoxExceptionFormValidatorsLogin::CODE_FORM_VALIDATION_LOGIN_PARALEL);
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>