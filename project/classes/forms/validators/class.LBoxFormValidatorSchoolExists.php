<?php
/**
 * zkontroluje, jestli skola existuje
 */
class LBoxFormValidatorSchoolExists extends LBoxFormValidator
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$records	= new SchoolsRecords(array("id" => $control->getValue()));
			if ($records->count() < 1) {
				throw new LBoxExceptionFormValidatorsMaybelline(LBoxExceptionFormValidatorsMaybelline::MSG_FORM_VALIDATION_CONTROL_SCHOOL_NOT_EXISTS,
																LBoxExceptionFormValidatorsMaybelline::CODE_FORM_VALIDATION_CONTROL_SCHOOL_NOT_EXISTS);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>