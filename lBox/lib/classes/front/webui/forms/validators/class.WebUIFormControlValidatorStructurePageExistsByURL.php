<?php
/**
 * zkontroluje, jestli stranka ve strukture NEexistuje
 */
class WebUIFormControlValidatorStructurePageExistsByURL extends ValidatorRecordNotExists
{
	protected $idColName	= "id";
	
	/**
	 * cache var
	 * @var LBoxFormControl
	 */
	protected $controlParentID;

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
	 * @param string $value
	* @return LBoxConfigItemStructure
	*/
	protected function getExistingRelevantRecord($value = "") {
		try {
			if (strlen($value) < 1) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormValidator::CODE_BAD_PARAM);
			}
			if (!$this->controlParentID instanceof LBoxFormControl) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_INSTANCE_VAR_INSTANCE_CONCRETE_NOTNULL, LBoxExceptionFormValidator::CODE_BAD_INSTANCE_VAR);
			}
			try {
				if ($parentID = $this->controlParentID->getValue()) {
					$parent	= LBoxConfigManagerStructure::getInstance()->getPageById($parentID);
					$urlParts	= explode("/", $value);
					foreach ($urlParts as $part) {
						if (strlen(trim($part)) > 0) {
							$out = $part;
						}
					}
					$urlPart	= LBoxUtil::getURLByNameString($out);
					$value		= $parent->url ."/". $urlPart ."/";
				}
				else {
					$value	= "/$value/";
				}
				$value		= preg_replace("/(\/+)/", "/", $value);
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
	
	/**
	 * @param LBoxFormControl $control
	 */
	public function setControlParentID(LBoxFormControl $control) {
		try {
			$this->controlParentID	= $control;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>