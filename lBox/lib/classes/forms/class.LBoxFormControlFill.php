<?php
/**
 * class LBoxFormControlFill
 */
class LBoxFormControlFill extends LBoxFormControl
{
	/**
	 * @var string
	 */
	protected $filenameTemplate = "lbox_form_control_fill.html";
	
	/**
	 * maxlength html attribute value
	 * @var int
	 */
	protected $lengthMax	= NULL;

	/**
	 * doplneno o parametr length
	 */
	public function __construct($name = "",  $label = "",  $default = "", $lengthMax	= NULL) {
		try {
			if ($lengthMax && (!is_numeric($lengthMax))) {
				throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_PARAM_INT, LBoxExceptionFormControl::CODE_BAD_PARAM);
			}
			$this->lengthMax	= $lengthMax;
			parent::__construct($name,  $label,  $default);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na $lengthMax
	 * @return int
	 */
	public function getLengthMax()	{
		try {
			return	$this->lengthMax;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>