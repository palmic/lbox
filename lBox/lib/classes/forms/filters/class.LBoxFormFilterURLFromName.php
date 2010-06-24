<?php
class LBoxFormFilterURLFromName extends LBoxFormFilter
{
	protected $controlName;

	public function filter(LBoxFormControl $control = NULL) {
		try {
			if (!$this->controlName instanceof LBoxFormControl) {
				throw new LBoxExceptionFormFilter("Bad instance var", LBoxExceptionFormFilter::CODE_BAD_INSTANCE_VAR);
			}
			if (strlen($control->getValue()) < 1) {
				return LBoxUtil::getURLByNameString(trim($this->controlName->getValue()));
			}
			else {
				return $control->getValue();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * setter controlu nazvu
	 * @param LBoxFormControl $control
	 */
	public function setControlName(LBoxFormControl $control) {
		try {
			$this->controlName	= $control;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>