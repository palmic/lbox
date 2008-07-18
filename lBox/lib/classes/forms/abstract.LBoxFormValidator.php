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
}
?>
