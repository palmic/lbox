<?php
/**
 * zkontroluje, jestli je hodnota absolutni URL
 */
class LBoxFormValidatorURLHTTPHTTPS extends LBoxFormValidator
{
	/**
	 * reg pattern kontroly URL
	 */
	protected $reg	= '(http(s?):\/\/|ftp:\/\/)*([[A-Za-z]][-[A-Za-z0-9]]*[[A-Za-z0-9]])(\.[[A-Za-z]][-[A-Za-z0-9]]*[[A-Za-z]])+(/[[A-Za-z]][-[A-Za-z0-9]]*[[A-Za-z0-9]])*(\/?)(/[[A-Za-z]][-[A-Za-z0-9]]*\.[[A-Za-z]]{3,5})?(\?([[A-Za-z0-9]][-_%[A-Za-z0-9]]*=[-_%[A-Za-z0-9]]+)(&([[A-Za-z0-9]][-_%[A-Za-z0-9]]*=[-_%[A-Za-z0-9]]+))*)?$';
	//protected $reg	= '(http(s?):\/\/|ftp:\/\/)*([[:alpha:]][-[:alnum:]]*[[:alnum:]])(\.[[:alpha:]][-[:alnum:]]*[[:alpha:]])+(/[[:alpha:]][-[:alnum:]]*[[:alnum:]])*(\/?)(/[[:alpha:]][-[:alnum:]]*\.[[:alpha:]]{3,5})?(\?([[:alnum:]][-_%[:alnum:]]*=[-_%[:alnum:]]+)(&([[:alnum:]][-_%[:alnum:]]*=[-_%[:alnum:]]+))*)?$';
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > 0)
			if (!preg_match('/'.$this->reg.'/', $control->getValue(), $regs)) {
			//if (!ereg($this->reg, $control->getValue(), $regs)) {
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