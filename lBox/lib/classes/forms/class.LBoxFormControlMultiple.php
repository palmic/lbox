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
	 * @param LBoxFormControl control 
	 */
	public function addControl(LBoxFormControl $control) {
		try {
			// multiple rozdelime a prekontrolujeme zvlast
			if ($control instanceof LBoxFormControlMultiple) {
				foreach ($control->getControls() as $subControl) {
					if (array_key_exists($subControl->getName(), $this->controls)) {
						throw new LBoxExceptionForm($control->getName() .": ". LBoxExceptionForm::MSG_FORM_CONTROL_DOES_EXISTS, LBoxExceptionForm::CODE_FORM_CONTROL_DOES_EXISTS);
					}
					$this->controls[$subControl->getName()]	= $subControl;
					if ($this->form) {
						$control->setForm($this->form);
						$this->form->addControl($control);
					}
				}
			}
			else {
				// kontrola unikatnosti sub controls mezi sebou
				if (array_key_exists($control->getName(), $this->controls)) {
					throw new LBoxExceptionForm($control->getName() .": ". LBoxExceptionForm::MSG_FORM_CONTROL_DOES_EXISTS, LBoxExceptionForm::CODE_FORM_CONTROL_DOES_EXISTS);
				}
				$this->controls[$control->getName()]	= $control;
				$control->setIsSubControl();
				
				/* pokud uz je nastaven form, musime provest kontrolu sub-controls na unikatnost apod - podle toho co Form vyzaduje
				 * toto si sama zaridi metoda LBoxForm::addControl(LBoxFormControlMultiple)
				 */
				if ($this->form) {
					$control->setForm($this->form);
					$this->form->addControl($this);
				}
			}
			$control->setPersist($this->isPersist);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci control podle jmena
	 * @param string $name
	 * @return LBoxFormControl
	 */
	public function getControlByName($name = "") {
		try {
			$name	= strtolower($name);
			if (!array_key_exists($name, $this->controls)) {
				throw new LBoxExceptionFormControl(	"\$name: ". LBoxExceptionFormControl::MSG_FORM_CONTROL_DOESNOT_EXISTS,
													LBoxExceptionFormControl::CODE_FORM_CONTROL_DOESNOT_EXISTS);
			}
			return $this->controls[$name];
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
	 * v pripade chyby nektereho z validatoru, vrati false, jinak true
	 * @return bool
	 */
	public function process() {
		try {
			if ($this->processed) {
				return;
			}
			$controlInvalid	= false;
			// nejdriv validace jednnotlivych controls
			foreach ($this->controls as $control) {
				if (!$control->process()) {
					$controlInvalid	= true;
				}
			}
			// potom multiple validace
			if (!$controlInvalid)
			foreach ($this->validators as $validator) {
				try {
					$validator->validate($this);
				}
				catch (Exception $e) {
					switch (true) {
						// chyba ve validaci
						case ($e instanceof LBoxExceptionFormValidator):
								$this->exceptionsValidations[$e->getCode()]	= $e;
							break;
						default:
							throw $e;
					}
				}
			}
			$this->processed	= true;
			if 		($controlInvalid) 	return false;
			else						return (count($this->exceptionsValidations) < 1);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * logic adapter to subcontrols
	 * @param bool $value
	 */
	public function setPersist($value = true) {
		try {
			$this->isPersist	= (bool)$value;
			foreach ($this->controls as $control) {
				$control->setPersist($value);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * prenese required na podrizene controls
	 */
	public function setRequired($value = true) {
		try {
			foreach ($this->controls as $control) {
				$control->setRequired($value);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>