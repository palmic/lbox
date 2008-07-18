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
	 *
	 * @param string value 
	 * @param string label 
	 */
	public function addOption( $value = "",  $label = "" ) {
		try {
			$this->options[$value]	= $label;
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
			if (!array_key_exists($value, $this->options)) {
				throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_FORM_CONTROL_VALUE_NOT_OPTION, LBoxExceptionFormControl::CODE_FORM_CONTROL_VALUE_NOT_OPTION);
			}
			return $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>