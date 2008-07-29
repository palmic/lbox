<?php
/**
 * class LBoxFormControlFile
 */
class LBoxFormControlFile extends LBoxFormControlFill
{
	/**
	 * @var protected
	 */
	protected $filenameTemplate = "lbox_form_control_file.html";

	/**
	 * cache funkce getValueFiles
	 * @var array
	 */
	protected $valueFiles		= array();
	
	/**
	 * cache funkce ckeckUpload
	 * @var bool
	 */
	protected $uploadChecked	= false;
	
	/**
	 * vraci nazev uploadovaneho souboru
	 *  - pouze pro zachovani perzistence s parent::getValue()
	 * @return string
	 */
	public function getValue() {
		try {
			if ($this->value !== NULL) {
				return $this->value;
			}
			$valueFiles			= $this->getValueFiles();
			return $this->value	= $valueFiles["name"];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci v poli vsechna sva files data
	 * @return array
	 */
	public function getValueFiles() {
		try {
			$this->checkUpload();
			if (count($this->valueFiles) > 0) {
				return $this->valueFiles;
			}
			return	$this->valueFiles	= $this->form->getSentDataByControlName($this->getName());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * zkontroluje upload souboru z hlediska bezpecnosti
	 * @throws LBoxException
	 */
	protected function checkUpload() {
		try {
			if ($this->uploadChecked) {
				return;
			}
			if (count($this->getForm()->getSentData()) > 0) {
				$dataFile	= $this->form->getSentDataByControlName($this->getName());
				if ($dataFile["error"]	!= UPLOAD_ERR_OK) {
					throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_FORM_CONTROL_FILE_UPLOAD_ERROR, LBoxExceptionFormControl::CODE_FORM_CONTROL_FILE_UPLOAD_ERROR);
				}
			}
			$this->uploadChecked	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>