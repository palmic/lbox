<?php
/**
 * zkontroluje, jestli vyplnena hodnota je telefonni cislo
 */
class LBoxFormValidatorPhone extends LBoxFormValidator
{
	/**
	 * regularni vyraz kontrolujici validitu telefonniho cisla
	 * @var string
	 */
	protected $regPhone	= "^\+([:digit:]{3})?([:digit:]{9})$";
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
var_dump($control->getValue());
var_dump($this->regPhone);
var_dump(ereg($this->regPhone, $control->getValue()));
var_dump(__FILE__);
			if (!ereg($this->regPhone, $control->getValue())) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_EMAIL_NOTVALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_EMAIL_NOTVALID);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>