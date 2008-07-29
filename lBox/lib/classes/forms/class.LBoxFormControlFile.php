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
	 * prepsana vzhledem k uploadu souboru
	 * @return string
	 */
	public function getValue() {
		try {
			if ($this->value !== NULL) {
				return $this->value;
			}
			return $this->value	= $this->form->getSentDataByControlName($this->name);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

}
?>