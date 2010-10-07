<?php
/**
 * zkontroluje, jestli vyplnena hodnota je cas podle 00:00
 */
class LBoxFormValidatorTimeHoursMinutes extends LBoxFormValidator
{
	/**
	 * regularni vyraz kontrolujici validitu
	 * @var string
	 */
	protected $regDateISO8601	= '^([\d]{2}):([\d]{2})$';
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) < 1) return;
			if (!preg_match('/'.$this->regDateISO8601.'/', $control->getValue(), $regs)) {
			//if (!ereg($this->regDateISO8601, $control->getValue(), $regs)) {
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