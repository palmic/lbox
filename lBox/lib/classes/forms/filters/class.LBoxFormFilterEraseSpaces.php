<?php
class LBoxFormFilterEraseSpaces extends LBoxFormFilter
{
	/**
	 * regularni vyraz pro smazani mezer
	 * @var string
	 */
	protected $regEraseSpaces	= '[\s]';
	//protected $regEraseSpaces	= '[[:space:]]';
	
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return preg_replace('/'.$this->regEraseSpaces.'/', "", $control->getValue());
			//return ereg_replace($this->regEraseSpaces, "", $control->getValue());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>