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
	 * @param LBoxForm $form
	 */
	public function __construct(LBoxForm $form) {
		$this->form	= $form;
	}
	
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