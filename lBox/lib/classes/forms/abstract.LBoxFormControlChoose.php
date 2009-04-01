<?php
/**
 * class LBoxFormControlChoose
 */
abstract class LBoxFormControlChoose extends LBoxFormControl
{
	/**
	 * @var array
	 */
	protected $options = array();

	/**
	 * @param LBoxFormControlOptionItem $option
	 * @throws LBoxExceptionFormControl
	 */
	public function addOption(LBoxFormControlOptionItem $option) {
		try {
			if (array_key_exists($option->getValue(), $this->options)) {
				throw new LBoxExceptionFormControl(	LBoxExceptionFormControl::MSG_FORM_CONTROL_VALUE_SET_ALREADY,
													LBoxExceptionFormControl::CODE_FORM_CONTROL_VALUE_SET_ALREADY);
			}
			$this->options[$option->getValue()]	= $option;
			$option->setControl($this);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci kompletni options
	 * @return array
	 */
	public function getOptions() {
		try {
			return $this->options;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * pretizena o kontrolu, jestli byla hodnota opravdu zadana v options
	 * @return string
	 */
	public function getValue() {
		try {
			$value	= parent::getValue();
			if (strlen($value) > 0) {
				if (!array_key_exists($value, $this->options)) {
					throw new LBoxExceptionFormControl("$value: ". LBoxExceptionFormControl::MSG_FORM_CONTROL_VALUE_NOT_OPTION, LBoxExceptionFormControl::CODE_FORM_CONTROL_VALUE_NOT_OPTION);
				}
			}
			return $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizena o kontrolu, jestli byla hodnota opravdu zadana v options
	 * @param $value
	 */
	public function setValue($value = NULL) {
		try {
			if (!array_key_exists($value, $this->options)) {
				throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_FORM_CONTROL_VALUE_NOT_OPTION, LBoxExceptionFormControl::CODE_FORM_CONTROL_VALUE_NOT_OPTION);
			}
			parent::setValue($value);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>