<?php
class ValidatorMetanode extends LBoxFormValidator
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			/*if (strlen($control->getValue()) > 0)
			if (!eregi($this->reg, $control->getValue())) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}*/
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>