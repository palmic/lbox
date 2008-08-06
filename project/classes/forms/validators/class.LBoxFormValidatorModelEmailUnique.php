<?php
class LBoxFormValidatorModelEmailUnique extends LBoxFormValidator
{
	/**
	 * kontroluje, jestli je predany email unikatni
	 */
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) < 1) {
				return;
			}
			$records	= new ModelsRecords(array("email" => $control->getValue()));
			if ($records->count() > 0) {
				throw new LBoxExceptionFormValidatorsMaybelline(	LBoxExceptionFormValidatorsMaybelline::MSG_FORM_VALIDATION_CONTROL_MODEL_EMAIL_NOTUNIQUE,
																	LBoxExceptionFormValidatorsMaybelline::CODE_FORM_VALIDATION_CONTROL_MODEL_EMAIL_NOTUNIQUE);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>