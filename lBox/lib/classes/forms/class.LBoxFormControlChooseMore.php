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
				$valuesDefault	= array();
				foreach ($this->options as $option) {
					$valuesDefault[]	= $option->getValue();
				}
				return $this->value = $valuesDefault;
			}
			$values	= $this->form->getSentDataByControlName($this->getName());
			foreach ((array)$values as $value) {
				if (strlen($value) > 0)
				if (!array_key_exists($value, $this->options)) {
					throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_FORM_CONTROL_VALUE_NOT_OPTION, LBoxExceptionFormControl::CODE_FORM_CONTROL_VALUE_NOT_OPTION);
				}
			}
			return $this->value = $values;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>