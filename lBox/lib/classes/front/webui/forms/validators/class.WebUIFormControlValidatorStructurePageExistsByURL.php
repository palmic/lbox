<?php
/**
 * zkontroluje, jestli stranka ve strukture NEexistuje
 */
class WebUIFormControlValidatorStructurePageExistsByURL extends ValidatorRecordNotExists
{
	protected $idColName	= "id";

	public function validate(LBoxFormControl $control = NULL) {
		try {
			if ($control->getValue()) {
				$idColName	= $this->idColName;
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
	 * @param mixed $value
	 * @return bool
	 */
	protected function recordNotExists($value	= "") {
		try {
			return !((bool)$this->getExistingRelevantRecord($value));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @param mixed $value
	* @return LBoxConfigItemStructure
	*/
	protected function getExistingRelevantRecord($value = "") {
		try {
			if (strlen($value) < 1) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormValidator::CODE_BAD_PARAM);
			}
			try {
				$value	= "/$value/";
				if ($page = LBoxConfigManagerStructure::getInstance()->getPageByUrl($value)) {
					return $page;
				}
			}
			catch (Exception $e) {
				switch ($e->getCode()) {
					case LBoxExceptionConfigStructure::CODE_NODE_BYURL_NOT_FOUND:
							NULL;
						break;
					default:
						throw $e;
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>