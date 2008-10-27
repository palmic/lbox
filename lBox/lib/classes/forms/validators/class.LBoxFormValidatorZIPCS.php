<?php
/**
 * zkontroluje, jestli je hodnota ceske PSC
 */
class LBoxFormValidatorZIPCS extends LBoxFormValidator
{
	/**
	 * reg pattern kontroly PSC
	 */
	protected $reg	= "^[[:digit:]]{5}$";
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > 0)
			if (!ereg($this->reg, $control->getValue())) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>