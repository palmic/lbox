<?php
class LBoxFormValidatorDiscussionBody extends  LBoxFormValidator
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > LBoxConfigManagerProperties::gpcn("form_max_length_discussion_text")) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_TOO_LONG,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_TOO_LONG);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>