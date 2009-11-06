<?php
/**
 * zkontroluje, jestli hodnota odpovida existujicimu radku v CSV
 * CSV musi v prvni linii obsahovat nazvy sloupcu
 */
abstract class ValidatorCSVLineExists extends LBoxFormValidator
{
	/**
	 * cache var
	 * @var string
	 */
	protected $filterColName		= "";
	
	/**
	 * nazev property s cestou k souboru s daty
	 * @var string
	 */
	protected $propertyNamePathData	= "";
	
	/**
	 * cache var
	 * @var resource
	 */
	protected $fileH;
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if ($control->getValue())
			if (!$this->recordExists($control->getValue())) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_IS_UNIQUE,
													 LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_IS_UNIQUE);
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
	 * getter na radek podle hodnoty $filterColName
	 * @param string $value
	 * @return array
	 */
	protected function getExistingRelevantRecord($value = "") {
		try {
			if (!is_string($value)) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormValidator::CODE_BAD_PARAM);
			}
			if (strlen($this->filterColName) < 1) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionFormValidator::CODE_BAD_INSTANCE_VAR);
			}
			if (!file_exists($this->getFilePath()) || filesize($this->getFilePath()) < 1) {
				return NULL;
			}
			$out					= array();
			$fileH					= $this->getFileH();
			$lineCount				= 1;
			$filterColumnOrder		= 0;
			$filterColumnOrderFound	= false;
			$dataColNames			= array();
			while (($data = fgetcsv($fileH, 1000, ";", '"')) !== FALSE) {
				if ($lineCount < 2) {
					$dataColNames	= $data;
					foreach ($dataColNames as $columnName) {
						if ($this->filterColName == $columnName) {
							$filterColumnOrderFound	= true;
						}
						$filterColumnOrder++;
					}
				}
				if (!$filterColumnOrderFound) {
					throw new LBoxExceptionFormValidator("Filter column name ". $this->filterColName . " not found on data file");
				}
				// line found
				if ($data[$filterColumnOrderFound] == $value) {
					for ($i = 0; $i < count($data); $i++) {
						$out[$dataColNames[$i]]	= $data[$i];
					}
					return $out;
					break;
				}
				
				$lineCount++;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na handler oteverneho filu
	 * @return resource
	 */
	protected function getFileH() {
		try {
			if (is_resource($this->fileH)) {
				return $this->fileH;
			}
			return $this->fileH = fopen($this->getFilePath(), "r");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na file path
	 * @return string
	 */
	protected function getFilePath() {
		try {
			if (strlen($this->propertyNamePathData) < 1) {
				throw new LBoxExceptionFormProcessor(LBoxExceptionFormProcessor::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionFormProcessor::CODE_BAD_INSTANCE_VAR);
			}
			$path	= LBoxConfigManagerProperties::gpcn($this->propertyNamePathData);
			$path	= str_replace("<project>", LBOX_PATH_PROJECT, $path);
			$path	= LBoxUtil::fixPathSlashes($path);
			return $path;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>