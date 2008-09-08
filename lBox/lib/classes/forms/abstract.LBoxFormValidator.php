<?php
/**
 * class LBoxFormValidator
 */
abstract class LBoxFormValidator
{
	/**
	 *
	 * @param LBoxFormControl control
	 * @throws LBoxExceptionFormValidator
	 */
	abstract public function validate(LBoxFormControl $control = NULL);
	
	/**
	 * prepsat v pripade, ze je pozadovana nejaka akce po uspesnem processingu formulare
	 */
	public function commitProcessSuccess () {
	}
}
?>
