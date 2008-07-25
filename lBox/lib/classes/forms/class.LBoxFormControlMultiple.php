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
			if (strlen($name) < 1) {
				throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormControl::CODE_BAD_PARAM);
			}
			$this->name		= $name;
			$this->label	= $label;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Prepsano o delegaci i na sub control 
	 */
	public function setForm(LBoxForm $form = NULL) {
		try {
			foreach ($this->controls as $control) {
				$control->setForm($form);
			}
			parent::setForm($form);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @param LBoxFormControl control 
	 */
	public function addControl(LBoxFormControl $control = NULL ) {
		try {
			// kontrola unikatnosti mezi sebou
			if (array_key_exists($control->getName(), $this->controls)) {
				throw new LBoxExceptionForm($control->getName() .": ". LBoxExceptionForm::MSG_FORM_CONTROL_DOES_EXISTS, LBoxExceptionForm::CODE_FORM_CONTROL_DOES_EXISTS);
			}
			$this->controls[$control->getName()]	= $control;
			/* pokud uz je nastaven form, musime provest kontrolu sub-controls na unikatnost apod - podle toho co Form vyzaduje
			 * toto si sama zaridi metoda LBoxForm::addControl(LBoxFormControlMultiple)
			 */
			if ($this->form) {
				$control->setForm($this->form);
				$this->form->addControl($this);
			}
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
	 * jeste pred tim, samozrejme spousti sve multiple validatory
	 */
	public function process() {
		try {
			if ($this->processed) {
				return;
			}
			// nejdriv validace jednnotlivych controls
			foreach ($this->controls as $control) {
				$control->process();
			}
			// potom multiple validace
			foreach ($this->validators as $validator) {
				$validator->validate($this);
			}
			$this->processed	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>