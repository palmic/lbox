<?php
class LBoxFormControlBool extends LBoxFormControl
{
	/**
	 * @var string
	 */
	protected $filenameTemplate = "lbox_form_control_bool.html";
	
	/**
	 * Pretizeno o default natvrdo
	 */
	public function __construct($name = "",  $label = "",  $default = "") {
		try {
			$default	= "1";
			parent::__construct($name,  $label,  $default);
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
					return parent(__get($name));
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
			return ($this->getValue() == $this->getDefault());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>