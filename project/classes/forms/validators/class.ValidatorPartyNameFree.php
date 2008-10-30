<?php
class ValidatorPartyNameFree extends LBoxFormValidator
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$records	= new PartiesRecords(array("name" => $control->getValue()));
			if ($records->count() > 0) {
				throw new LBoxExceptionFormValidatorsMaybelline(LBoxExceptionFormValidatorsMaybelline::MSG_FORM_VALIDATION_CONTROL_PARTY_EXISTS,
																LBoxExceptionFormValidatorsMaybelline::CODE_FORM_VALIDATION_CONTROL_PARTY_EXISTS);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>