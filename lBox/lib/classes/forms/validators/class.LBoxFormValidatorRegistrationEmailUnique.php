<?php
class LBoxFormValidatorRegistrationEmailUnique extends LBoxFormValidator
{
	/**
	 * kontroluje, jestli je predany email unikatni
	 */
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$records	= new XTUsersRecords(array("email" => $control->getValue()));
			if ($records->count() > 0 && ($records->current()->confirmed > 0)) {
				throw new LBoxExceptionFormValidatorRegistration(	LBoxExceptionFormValidatorsRegistration::MSG_FORM_VALIDATION_REGISTRATION_EMAIL_NOTUNIQUE,
																	LBoxExceptionFormValidatorsRegistration::CODE_FORM_VALIDATION_REGISTRATION_EMAIL_NOTUNIQUE);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>