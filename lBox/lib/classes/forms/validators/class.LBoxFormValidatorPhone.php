<?php
/**
 * zkontroluje, jestli vyplnena hodnota je telefonni cislo
 */
class LBoxFormValidatorPhone extends LBoxFormValidator
{
	/**
	 * regularni vyraz kontrolujici validitu telefonniho cisla
	 * bere tyto 4 formaty:
	 * - +420777666333
	 * - 420777666333
	 * - 777666333
	 * - 666333
	 * @var string
	 */
	protected $regPhone	= '^(\+)?([\d]{3})?([\d]{3})?([\d]{6})$';
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > 0)
			if (!preg_match('/'.$this->regPhone.'/', $control->getValue())) {
			//if (!ereg($this->regPhone, $control->getValue())) {
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