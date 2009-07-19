<?php
/**
 * zkontroluje, jestli hodnota odpovida existujicimu recordu
 */
abstract class ValidatorRecordNotExists extends ValidatorRecordExists
{
	/**
	 * id kontrolovaneho recordu
	 * @var mixed
	 */
	protected $idChecking		= "";
	
	/**
	* umoznuje volitelne nastavit id editovaneho recordu pro moznost kontroly, jestli existujici nalezeny record neni nahodou ten samy
	* @param $id
	*/
	public function __construct($id = "") {
		try {
			if ($id) {
				$recordClassName	= $this->recordClassName;
				$idColName			= $this->getIDColname();
				$recordsClassName	= eval("return $recordClassName::\$itemsType;");
				$tmpRecords			= new $recordsClassName(array($idColName => $id));
				if ($tmpRecords->count() < 1) {
					throw new LBoxExceptionFormValidator("Invalid ignore-ID given - it does not exists in database!");
				}
				$this->idChecking	= $id;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function validate(LBoxFormControl $control = NULL) {
		try {
			if ($control->getValue()) {
				$idColName	= $this->getIDColname();
				if (!	$this->recordNotExists($control->getValue())
					&&	$this->idChecking !== $this->getExistingRelevantRecord($control->getValue())->$idColName) {
					throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
														 LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
				}
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