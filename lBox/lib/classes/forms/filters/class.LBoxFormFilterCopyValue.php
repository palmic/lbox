<?php
/**
 * copy value from src control
 */
class LBoxFormFilterCopyValue extends LBoxFormFilter
{
	/**
	 * src value control
	 * @var LBoxFormControl
	 */
	protected $controlSRC;
	
	/**
	 * flag
	 * @var bool
	 */
	protected $copyOnlyEmpty = false;
	
	public function filter(LBoxFormControl $control = NULL) {
		try {
			if (!$this->controlSRC instanceof LBoxFormControl) {
				throw new LBoxExceptionFormValidator("Bad instance var", LBoxExceptionFormValidator::CODE_BAD_INSTANCE_VAR);
			}
			if ($this->copyOnlyEmpty) {
				return (strlen($control->getValue()) < 1) ? $this->controlSRC->getValue() : $control->getValue();
			}
			else {
				return $this->controlSRC->getValue();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * source value control setter
	 * @param LBoxFormControl $control
	 */
	public function setControlSRC(LBoxFormControl $control) {
		try {
			$this->controlSRC	= $control;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * does copy only if target control is not already filled
	 * @param bool $value
	 * @throws LBoxExceptionFormValidator
	 */
	public function setCopyOnlyEmpty($value = true) {
		try {
			$this->copyOnlyEmpty	= $value;
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}
?>