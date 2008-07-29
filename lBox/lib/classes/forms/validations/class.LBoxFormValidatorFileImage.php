<?php
/**
 * zkontroluje, jestli vyplnena hodnota je email
 */
class LBoxFormValidatorFileImage extends LBoxFormValidator
{
	/**
	 * regularni vyraz kontrolujici validitu emailu
	 * @var string
	 */
	protected $extImages	= array(
									"jpg",
									"jpeg",
									"gif",
									"png",
									);
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$valueFiles	= $control->getValueFiles();
			if (!$this->isFileNameImage($valueFiles["name"])) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_FILE_NOT_IMAGE,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_FILE_NOT_IMAGE);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * kontroluje, jestli je predany filename nazev obrazku
	 * @param string $fileName
	 * @return bool
	 */
	protected function isFileNameImage($fileName = "") {
		try {
			return (is_numeric(array_search(strtolower(end(explode(".", $fileName))), $this->extImages)));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>