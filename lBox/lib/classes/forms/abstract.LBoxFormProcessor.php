<?php
/**
 * class LBoxFormProcessor
 */
abstract class LBoxFormProcessor
{
	/**
	 * @var LBoxForm
	 */
	protected $form;
	
	/**
	 * form setter
	 * @param LBoxForm $form
	 */
	public function setForm(LBoxForm $form) {
		$this->form	= $form;
	}
	
	/**
	 * @throws LBoxExceptionFormProcessor
	 */
	abstract public function process();
}
?>