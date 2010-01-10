<?php
/**
 * validator kontrolujici login pomoci nicku a hesla
 *
 */
class LBoxFormValidatorLogin extends LBoxFormValidator
{
	/**
	 * kontroluje, jestli se hesla shoduji
	 */
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$records	= $this->getRecordsByControls($control);
			
			if ($records->count() < 1) {
				throw new LBoxExceptionFormValidatorsLogin(	LBoxExceptionFormValidatorsLogin::MSG_FORM_VALIDATION_LOGIN_NOTSUCCES,
															LBoxExceptionFormValidatorsLogin::CODE_FORM_VALIDATION_LOGIN_NOTSUCCES);
			}
			if ($records->current()->confirmed < 1) {
				throw new LBoxExceptionFormValidatorsLogin(	LBoxExceptionFormValidatorsLogin::MSG_FORM_VALIDATION_LOGIN_NOTCONFIRMED,
															LBoxExceptionFormValidatorsLogin::CODE_FORM_VALIDATION_LOGIN_NOTCONFIRMED);
			}
		}
		catch (Exception $e) {
			// workaround DEBILNIHO A ABSOLUTNE NELOGICKYHO CHOVANI MSSQL!!!
			if (strlen(strstr($e->getFile(), "DbMssql")) > 0) {
				if (strlen(strstr($e->getMessage(), "to a column of data type int")) > 0) {
					throw new LBoxExceptionFormValidatorsLogin(	LBoxExceptionFormValidatorsLogin::MSG_FORM_VALIDATION_LOGIN_NOTSUCCES,
																LBoxExceptionFormValidatorsLogin::CODE_FORM_VALIDATION_LOGIN_NOTSUCCES);
				}
			}
			throw $e;
		}
	}
	
	protected function getRecordsByControls(LBoxFormControlMultiple $control) {
		try {
			$filter	= array();
			foreach ($control->getControls() as $ctrlName => $control) {
				$filter[$ctrlName]	= $control->getValue();
			}
			return new XTUsersRecords($filter);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>