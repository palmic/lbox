<?php
/**
 * class LBoxFormControlChooseMore
 */
class LBoxFormControlChooseMore extends LBoxFormControlChoose
{
	/**
	 * @var string
	 */
	protected $filenameTemplate = "lbox_form_control_choose_more.html";

	/**
	 * pretizena o upravy tykajici se multiple volby
	 * @return string
	 */
	public function getValue() {
		try {
			if ($this->value !== NULL) {
				return $this->value;
			}
			if ($this->isDisabled()) {
				return $this->value	= $this->default;
			}
			if ($this->form->wasSent()) {
				$values	= $this->form->getSentDataByControlName($this->getName());
				foreach ((array)$values as $value) {
					if (strlen($value) > 0)
					if (!array_key_exists($value, $this->options)) {
						throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_FORM_CONTROL_VALUE_NOT_OPTION, LBoxExceptionFormControl::CODE_FORM_CONTROL_VALUE_NOT_OPTION);
					}
				}
				$this->value = $values;
			}
			else {
				$this->value	= $this->default;
			}
			return (array)$this->value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>