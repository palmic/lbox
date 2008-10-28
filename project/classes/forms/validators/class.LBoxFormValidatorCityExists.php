<?php
/**
 * zkontroluje, jestli mesto existuje
 */
class LBoxFormValidatorCityExists extends LBoxFormValidator
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$records	= new CitiesRecords(array("id" => $control->getValue()));
			if ($records->count() < 1) {
				throw new LBoxExceptionFormValidatorsMaybelline(LBoxExceptionFormValidatorsMaybelline::MSG_FORM_VALIDATION_CONTROL_CITY_NOT_EXISTS,
																LBoxExceptionFormValidatorsMaybelline::CODE_FORM_VALIDATION_CONTROL_CITY_NOT_EXISTS);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>