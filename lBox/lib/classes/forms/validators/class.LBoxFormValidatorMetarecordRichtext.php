<?php
class LBoxFormValidatorMetarecordRichtext extends LBoxFormValidator
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			/*if (strlen($control->getValue()) > 0)
			if (!eregi($this->reg, $control->getValue())) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_EMAIL_NOTVALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_EMAIL_NOTVALID);
			}*/
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>