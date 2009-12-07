<?php
abstract class LBoxFormFilterFloatDecimalPlaces extends LBoxFormFilter
{
	/**
	 * kolik desetinnych mist
	 * @var int
	 */
	protected $decimalPlaces;
	
	public function filter(LBoxFormControl $control = NULL) {
		try {
			if (!is_int($this->decimalPlaces) || $this->decimalPlaces < 1) {
				throw new LBoxExceptionFormFilter(LBoxExceptionFormFilter::MSG_INSTANCE_VAR_INTEGER_NOTNULL, LBoxExceptionFormFilter::CODE_BAD_INSTANCE_VAR);
			}
			return is_numeric($control->getValue()) ? number_format($control->getValue(), $this->decimalPlaces) : $control->getValue();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>