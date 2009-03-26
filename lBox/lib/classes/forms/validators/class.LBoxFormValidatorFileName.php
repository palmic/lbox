<?php
/**
 * zkontroluje, jestli je nazev souboru validni (vuci kodovani atd)
 */
class LBoxFormValidatorFileName extends LBoxFormValidator
{
	/**
	 * regularni vyraz kontrolujici vymezeni povolenych znaku v nazvu filu
	 * @var string
	 */
	protected $regSigns	= '^([[:alnum:]_\.\-])+$';

	public function validate(LBoxFormControl $control = NULL) {
		try {
			if ($control instanceof LBoxFormControlFile) {
				$valueFiles	= $control->getValueFiles();
				$value		= $valueFiles["name"];
			}
			else {
				$value		= $control->getValue();
			}
			if (strlen($value) > 0)
			if (!ereg($this->regSigns, $value)) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_FILENAME_INVALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_FILENAME_INVALID);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>