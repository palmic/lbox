<?php
/**
 * class LBoxFormFilter
 */
abstract class LBoxFormFilter
{
	/**
	 *
	 * @param LBoxFormControl control 
	 * @return string
	 */
	abstract public function filter(LBoxFormControl $control = NULL);
}
?>