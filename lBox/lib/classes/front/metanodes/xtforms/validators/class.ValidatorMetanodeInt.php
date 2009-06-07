<?php
class ValidatorMetanodeInt extends ValidatorMetanode
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			parent::validate($control);
			if (strlen($control->getValue()) > 0)
			if (!is_int($control->getValue())) {
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