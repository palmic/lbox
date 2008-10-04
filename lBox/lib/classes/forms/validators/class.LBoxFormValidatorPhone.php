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
	protected $regPhone	= '^(\+[[:digit:]]{3})?([[:digit:]]{9})$';
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > 0)
			if (!ereg($this->regPhone, $control->getValue())) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_PHONE_NOTVALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_PHONE_NOTVALID);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>