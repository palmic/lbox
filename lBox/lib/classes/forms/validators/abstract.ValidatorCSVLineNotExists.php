<?php
/**
 * zkontroluje, jestli hodnota NEodpovida existujicimu radku v CSV
 * CSV musi v prvni linii obsahovat nazvy sloupcu
 */
abstract class ValidatorCSVLineNotExists extends ValidatorCSVLineExists
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if ($control->getValue())
			if ($this->recordExists($control->getValue())) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_IS_NOT_UNIQUE,
													 LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_IS_NOT_UNIQUE);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>