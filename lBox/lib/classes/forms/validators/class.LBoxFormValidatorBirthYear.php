<?php
/**
 * zkontroluje, jestli hodnota odpovida pravdepodobnemu roku narozeni
 */
class LBoxFormValidatorBirthYear extends LBoxFormValidator
{
	/**
	 * maximalni tolerovany vek
	 * @var int
	 */
	protected $maxAge	= 100;
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (!$this->isValidBirthYear($control->getValue())) {
				throw new LBoxExceptionFormValidatorMaybelline(LBoxExceptionFormValidator::MSG_FORM_VALIDATION_BIRTHYEAR_NOTVALID,
																LBoxExceptionFormValidator::CODE_FORM_VALIDATION_BIRTHYEAR_NOTVALID);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci, jestli je hodnotu mozno povazovat za rok narozeni
	 * @param int $year
	 * @return bool
	 */
	protected function isValidBirthYear($year	= 0) {
		try {
			if (!is_numeric($year)) {
				return false;
			}
			if ($year < date("Y")-$this->maxAge) {
				return false;
			}
			if ($year > date("Y")) {
				return false;
			}
			return true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>