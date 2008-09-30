<?php
/**
 * zkontroluje, jestli vyplnena hodnota je datum podle ISO 8601
 */
class LBoxFormValidatorDateISO8601 extends LBoxFormValidator
{
	/**
	 * regularni vyraz kontrolujici validitu
	 * @var string
	 */
	protected $regDateISO8601	= "^([[:digit:]]{4})-([[:digit:]]{2})-([[:digit:]]{2})$";
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) < 1) return;
			if (!ereg($this->regDateISO8601, $control->getValue(), $regs)) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
			if ($regs[1] < 1979) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
			if ($regs[2] < 1 || $regs[2] > 12) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
			if ($regs[3] < 1 || $regs[3] > 31) {
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