<?php
/**
 * zkontroluje, jestli je hodnota absolutni URL
 */
class LBoxFormValidatorURLHTTPHTTPS extends LBoxFormValidator
{
	/**
	 * reg pattern kontroly URL
	 */
    protected $reg = "((https?|ftp)\:\/\/)?([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?([a-z0-9-.]*)\.([a-z]{2,3})(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?"; 
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) > 0)
			if (!preg_match('/'.$this->reg.'/', $control->getValue(), $regs)) {
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