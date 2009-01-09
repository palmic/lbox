<?php
/**
 * zkontroluje, jestli je hodnota absolutni URL
 */
class LBoxFormValidatorURLHTTPHTTPS extends LBoxFormValidator
{
	/**
	 * reg pattern kontroly URL
	 */
	protected $reg	= '(http(s?):\/\/|ftp:\/\/)*([[:alpha:]][-[:alnum:]]*[[:alnum:]])(\.[[:alpha:]][-[:alnum:]]*[[:alpha:]])+(/[[:alpha:]][-[:alnum:]]*[[:alnum:]])*(\/?)(/[[:alpha:]][-[:alnum:]]*\.[[:alpha:]]{3,5})?(\?([[:alnum:]][-_%[:alnum:]]*=[-_%[:alnum:]]+)(&([[:alnum:]][-_%[:alnum:]]*=[-_%[:alnum:]]+))*)?$';
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > 0)
			if (!ereg($this->reg, $control->getValue(), $regs)) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>