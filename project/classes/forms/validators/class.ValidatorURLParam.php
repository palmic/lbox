<?php
class ValidatorURLParam extends LBoxFormValidatorEmail
{
	/**
	 * regularni vyraz kontrolujici validitu URL param
	 * @var string
	 */
	protected $reg	= "^([_a-z0-9-]+)$";
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > 0)
			if (!eregi($this->reg, $control->getValue())) {
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