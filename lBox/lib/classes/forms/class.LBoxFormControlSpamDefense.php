<?php
/**
 * class LBoxFormControlSpamDefense
 */
class LBoxFormControlSpamDefense extends LBoxFormControlFillHidden
{
	/**
	 * @var string
	 */
	protected $filenameTemplate = "lbox_form_control_spamdefense.html";

	protected $name				= "sd1";

	protected $default			= "sdf2sdf";
	
	/**
	 * rendered flag z duvodu neopakovani deklaraci js
	 * @var bool
	 */
	protected static $rendered	= false;

	public function __construct() {
	}

	/**
	 * pridana validace natvrdo - bez js neni mozne, aby form prosel
	 */
	public function process() {
		try {
			if ($this->getValue() != $this->default) {
				$this->exceptionsValidations[LBoxExceptionFormValidator::CODE_FORM_VALIDATION_SPAMDEFENSEJS_NOT_PASS]
					= new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_SPAMDEFENSEJS_NOT_PASS,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_SPAMDEFENSEJS_NOT_PASS);
			}
			return parent::process();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci kompletni javascript code pro aktivaci antispamu
	 * @return string
	 */
	public function getJSLib() {
		if (self::$rendered) {
			return "";
		}

		$name	= $this->name;
		$value	= $this->default;
		$out	 = "";
		$out	.= "sD = function (fEl, formName) {\n";
		$out	.= "	var fc = document.createElement('input');\n";
		$out	.= "		fc.type = 'hidden';\n";
		$out	.= "		fc.name = formName ? formName+'[$name]' : '$name';\n";
		$out	.= "		fc.value = '$value';\n";
		$out	.= "		fEl.appendChild(fc);\n";
		$out	.= "};";
		
		self::$rendered	= true;
		
		return $out;
	}

	/**
	 * vraci kompletni javascript code pro inicializaci antispamu
	 * @return string
	 */
	public function getJSInit() {
		$name	= $this->getForm()->getName();
		$out	  = "";
		$out	 .= "sD(document.getElementById('frm-$name'), '$name');";
		return $out;
	}
}
?>