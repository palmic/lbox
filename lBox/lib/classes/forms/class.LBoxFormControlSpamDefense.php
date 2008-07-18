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

	public function __construct() {
	}

	/**
	 * vraci kompletni javascript code pro aktivaci antispamu
	 * @return string
	 */
	public function getJSLib() {
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