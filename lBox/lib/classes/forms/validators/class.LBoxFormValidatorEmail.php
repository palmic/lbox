<?php
/**
 * zkontroluje, jestli vyplnena hodnota je email
 */
class LBoxFormValidatorEmail extends LBoxFormValidator
{
	/**
	 * regularni vyraz kontrolujici validitu emailu
	 * @var string
	 */
	protected $reg	= "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > 0)
			if (!eregi($this->reg, $control->getValue())) {
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