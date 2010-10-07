<?php
/**
 * filtr pro pripadne doplneni predvolby ceskeho telefonniho cisla
 */
class LBoxFormFilterPhoneNumberCS extends LBoxFormFilter
{
	/**
	 * regularni vyraz pro zjisteni, jestli
	 * @var string
	 */
	protected $regPhone	= '^([\d]{9})$';
	//protected $regPhone	= '^([[:digit:]]{9})$';
	
	/**
	 * predvolba pro konkretni zemi
	 * @var unknown_type
	 */
	protected $phoneNumberPrefix	= "+420";
	
	public function filter(LBoxFormControl $control = NULL) {
		try {
			if (preg_match('/'.$this->regPhone.'/', $control->getValue())) {
			//if (ereg($this->regPhone, $control->getValue())) {
				return $this->phoneNumberPrefix . $control->getValue();
			}
			return $control->getValue();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>