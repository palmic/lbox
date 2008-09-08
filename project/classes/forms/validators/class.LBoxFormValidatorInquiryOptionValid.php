<?php
class LBoxFormValidatorInquiryOptionValid extends  LBoxFormValidator
{
	/**
	 * kontroluje jestli volba, pro kterou uzivatel hlasuje nalezi aktivni ankete
	 */
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$records	= new InquiriesOptionsRecords(array("id" => $control->getValue()));
			// neexistuje
			if ($records->count() < 1) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
			// neni aktivni
			if (!$records->current()->getInquiry()->is_active) {
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