<?php
class ValidatorPartyEmails extends LBoxFormValidatorEmail
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen(trim($control->getValue())) < 1) {
				return;
			}
			foreach (explode(",", $control->getValue()) as $email) {
				if (!eregi($this->reg, trim($email))) {
					throw new LBoxExceptionFormValidatorsMaybelline(LBoxExceptionFormValidatorsMaybelline::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
																	LBoxExceptionFormValidatorsMaybelline::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>