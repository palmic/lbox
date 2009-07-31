<?php
/**
 * class LBoxFormFilter
 */
abstract class LBoxFormFilter
{
	/**
	 * optionaly control with source data - to pass data from one form control to another possibilities
	 * @var LBoxFormControl
	 */
	protected $control;
	
	/**
	 * to define another control as source data bearer 
	 * @param LBoxFormControl $control
	 * @return unknown_type
	 */
	public function __construct(LBoxFormControl $control = NULL) {
		$this->control	= $control;
	}

	/**
	 *
	 * @param LBoxFormControl control 
	 * @return string
	 */
	abstract public function filter(LBoxFormControl $control = NULL);
	
	/**
	 * getter for source data control if defined
	 * @return LBoxFormControl
	 */
	public function getControl() {
		try {
			return $this->control;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>