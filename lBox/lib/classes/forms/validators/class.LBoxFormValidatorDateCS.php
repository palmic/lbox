<?php
/**
 * zkontroluje, jestli vyplnena hodnota je datum podle Ceskych zvyklosti
 */
class LBoxFormValidatorDateCS extends LBoxFormValidator
{
	/**
	 * regularni vyraz kontrolujici validitu
	 * @var string
	 */
	protected $regDateCS	= '^([\d]{2})\.([\d]{2})\.([\d]{4})$';
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) < 1) return;
			if (!preg_match('/'.$this->regDateCS.'/', $control->getValue(), $regs)) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
			if ($regs[2] < 1979) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
			if ($regs[2] < 1 || $regs[2] > 12) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
			if ($regs[1] < 1 || $regs[3] > 31) {
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