<?php
class ValidatorURLParam extends LBoxFormValidatorEmail
{
	/**
	 * regularni vyraz kontrolujici validitu URL param
	 * @var string
	 */
	protected $reg	= "^([\w-]+)$";
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > 0)
			if (!preg_match('/'.$this->reg.'/i', $control->getValue())) {
			//if (!eregi($this->reg, $control->getValue())) {
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