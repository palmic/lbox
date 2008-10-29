<?php
class LBoxFormControlBool extends LBoxFormControl
{
	/**
	 * @var string
	 */
	protected $filenameTemplate = "lbox_form_control_bool.html";
	
	/**
	 * pridana logika default volby zaskrtavani
	 */
	public function __construct($name = "",  $label = "",  $default = "") {
		try {
			parent::__construct($name,  $label,  (bool)$default ? "1" : "0");
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * defaultni getter
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		try {
			switch ($name) {
				case "getSelected":
					return $this->isSelected() ? "selected" : "";
				break;
				case "getChecked":
					return $this->isSelected() ? "checked" : "";
				break;
				default:
					return parent::__get($name);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci true v pripade, ze byla tato volba zvolena
	 * @return bool
	 * @access public
	 */
	public function isSelected() {
		try {
			if (!$this->form instanceof LBoxForm) {
				throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_FORM_CONTROL_FORM_NOT_SET, LBoxExceptionFormControl::CODE_FORM_CONTROL_FORM_NOT_SET);
			}
			if ($this->form->wasSent()) {
				return (bool)$this->form->getSentDataByControlName($this->getName());
			}
			else {
				return (bool)$this->getDefault();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizena o force bool
	 * @param $value
	 */
	public function setValue($value = NULL) {
		try {
			parent::setValue((bool)$value);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>