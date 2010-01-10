<?php
/**
 * zkontroluje, jestli vyplnena hodnota je zip archive
 */
class LBoxFormValidatorFileZIP extends LBoxFormValidator
{
	/**
	 * regularni vyraz kontrolujici validitu emailu
	 * @var string
	 */
	protected $extImages	= array(
									"zip",
									);

	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > 0)
			if (!$this->isFileZip($control)) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_FILE_NOT_ZIP,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_FILE_NOT_ZIP);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * checkuje, jestli jde skutecne o pouzitelny zip
	 * @return bool
	 */
	protected function isFileZip(LBoxFormControl $control) {
		try {
			$valueFiles	= $control->getValueFiles();
			return $this->isFileNameOK($valueFiles["name"]);
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * kontroluje, jestli je predany filename nazev obrazku
	 * @param string $fileName
	 * @return bool
	 */
	protected function isFileNameOK($fileName = "") {
		try {
			return (is_numeric(array_search(strtolower(end(explode(".", $fileName))), $this->extImages)));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>