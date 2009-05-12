<?php
abstract class LBoxFormControlOptionItem
{
	/**
	 * hodnota option, ktera bude odeslana formularem v pripade, ze tato option bude zvolena
	 * @var string
	 */
	protected $value	= "";

	/**
	 * hodnota option, ktera bude zobrazena uzivateli
	 * @var string
	 */
	protected $label	= "";
	
	/**
	 * nadrazeny control
	 * @var LBoxFormControlChoose
	 */
	protected $control	= "";
	
	/**
	 * pole volne definovatelnych parametru
	 * @var array
	 */
	protected $params	= array();

	/**
	 * @param string value
	 * @param string label
	 */
	public function __construct( $value = "",  $label = "" ) {
		try {
			if (strlen($value) < 1) {
				throw new LBoxExceptionFormControl("\$value: ". LBoxExceptionFormControl::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormControl::CODE_BAD_PARAM);
			}
			if (strlen($label) < 1) {
				throw new LBoxExceptionFormControl("\$label: ". LBoxExceptionFormControl::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormControl::CODE_BAD_PARAM);
			}
			$this->value	= $value;
			$this->label	= $label;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * setter pro params
	 * @param string $name
	 * @param mixed $value
	 * @throws LBoxException
	 */
	public function __set($name = "", $value = "") {
		if (strlen($name) < 1) {
			throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormControl::CODE_BAD_PARAM);
		}
		$this->params[$name]	= $value;
	}

	/**
	 * defaultni getter
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		try {
			if (array_key_exists($name, $this->params)) {
				return $this->params[$name];
			}
			switch ($name) {
				case "getSelected":
					return $this->isSelected() ? "selected" : "";
				break;
				case "getChecked":
					return $this->isSelected() ? "checked" : "";
				break;
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
			return (is_numeric(array_search($this->getValue(), (array)$this->control->getValue())));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return string
	 */
	public function getValue() {
		try {
			return $this->value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		try {
			return $this->label;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * nastavuje se vnitrne v LBoxFormControlChoose, nepouzivat z venci!
	 * @param LBoxFormControlChoose control
	 */
	public function setControl(LBoxFormControlChoose $control) {
		try {
			$this->control	= $control;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}