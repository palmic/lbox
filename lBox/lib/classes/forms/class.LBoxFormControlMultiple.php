<?php
/**
 * class LBoxFormControlMultiple
 * control spravujici vic controls najednou
 *  - mozno pouzit naproklad klasicky na passwords
 *  - umoznuje validace nad vice controls najednou
 */
class LBoxFormControlMultiple extends LBoxFormControl
{
	/**
	 * @var protected
	 */
	protected $controls = array();

	/**
	 * @var protected
	 */
	protected $filenameTemplate = "lbox_form_control_multiple.html";

	/**
	 *
	 * @param string name 
	 * @param string label 
	 */
	public function __construct( $name = "",  $label = "" ) {
		try {
			$this->name		= $name;
			$this->label	= $label;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @param LBoxFormControl control 
	 */
	public function addControl( $control = NULL ) {
		try {
			$this->controls[]	= $control;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci sve controls v poli - pro sablonu
	 * @return array
	 */
	public function getControls() {
		try {
			return $this->controls;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * prepsano o processing controls, ktere obsahuje
	 */
	public function process() {
		try {
			foreach ($this->controls as $control) {
				$control->process();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>