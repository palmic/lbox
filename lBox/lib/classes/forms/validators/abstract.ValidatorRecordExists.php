<?php
/**
 * zkontroluje, jestli hodnota odpovida existujicimu recordu
 */
abstract class ValidatorRecordExists extends LBoxFormValidator
{
	/**
	 * cache var
	 * @var string
	 */
	protected $filterColName	= "";
	
	/**
	 * record type
 	 * @var string
 	 */
	protected $recordClassName 	= "";
	
	/**
	 * cache var
	 * @var array
	 */
	protected $existingRelevantRecordsByValues	= array();
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if ($control->getValue())
			if (!$this->recordExists($control->getValue())) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
													 LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci, jestli record v systemu existuje podle predane hodnoty 
	 * @param mixed $value
	 * @return bool
	 */
	protected function recordExists($value	= "") {
		try {
			return (bool)$this->getExistingRelevantRecord($value);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci className souvisejici collekce records
	 * @return string
	 */
	protected function getClassNameRecords() {
		try {
			if (strlen($this->recordClassName) < 1) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionFormValidator::CODE_BAD_INSTANCE_VAR);
			}
			$classNameRecord	= $this->recordClassName;
			$classNameRecords	= eval("return $classNameRecord::\$itemsType;");
			return $classNameRecords;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci colname podle, ktereho chceme zjistovat, jestli record existuje (defaultne IDColname)
	 * @return string
	 */
	protected function getFilterColName() {
		try {
			if (strlen($this->filterColName) > 0) {
				return $this->filterColName;
			}
			else {
				return $this->getIDColname();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	* getter na IDColname
	* @return string
	*/
	protected function getIDColname() {
		try {
			$recordClassName	= $this->recordClassName;
			return				eval("return $recordClassName::\$idColName;");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	* getter na existujici record pokud existuje
	* - pro jeho ziskani pro kontrolu zda se neshoduje s kontrolovanym a tim nema byt ignorovan
	 * @param mixed $value
	* @return AbstractRecrdLBox
	*/
	protected function getExistingRelevantRecord($value = "") {
		try {
			if ($this->existingRelevantRecordsByValues[$value] instanceof AbstractRecordLBox) {
				return $this->existingRelevantRecordsByValues[$value];
			}
			if (strlen($value) < 1) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormValidator::CODE_BAD_PARAM);
			}
			$classNameRecords	= $this->getClassNameRecords();
			$records			= new $classNameRecords(array($this->getFilterColName() => $value));
			if ($records->count() > 0) {
				return $this->existingRelevantRecordsByValues[$value] = $records->current();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>