<?php
class FilterEraseSpaces extends LBoxFormFilter
{
	/**
	 * regularni vyraz pro smazani mezer
	 * @var string
	 */
	protected $regEraseSpace	= "[:space:]";
	
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return ereg_replace($this->regEraseSpace, "", $control->getValue());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>