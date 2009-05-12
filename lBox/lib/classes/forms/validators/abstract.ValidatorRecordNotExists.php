<?php
/**
 * zkontroluje, jestli hodnota odpovida existujicimu recordu
 */
abstract class ValidatorRecordNotExists extends ValidatorRecordExists
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if ($control->getValue())
			if (!$this->recordNotExists($control->getValue())) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
													 LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci, jestli record v systemu NEexistuje podle predane hodnoty 
	 * @param mixed $value
	 * @return bool
	 */
	protected function recordNotExists($value	= "") {
		try {
			if (strlen($value) < 1) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormValidator::CODE_BAD_PARAM);
			}
			$classNameRecords	= $this->getClassNameRecords();
			$records			= new $classNameRecords(array($this->getFilterColName() => $value));
			return $records->count() < 1;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>