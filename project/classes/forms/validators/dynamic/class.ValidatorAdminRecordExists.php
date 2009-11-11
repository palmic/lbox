<?php
/**
 * zkontroluje jestli record podle hodnoty controlu existuje podle nastaveneho colname
 */
class ValidatorAdminRecordExists extends ValidatorRecordExists
{
	/**
	 * vnejsi setter na record className
	 * @param string $className
	 */
	public function setRecordClassName($className = "") {
		try {
			if (strlen($className) < 1) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormValidator::CODE_BAD_PARAM);
			}
			$this->recordClassName	= $className;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vnejsi setter na filter colname
	 * @param string $colName
	 */
	public function setFilterColName($colName = "") {
		try {
			if (strlen($colName) < 1) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormValidator::CODE_BAD_PARAM);
			}
			$this->filterColName	= $colName;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>