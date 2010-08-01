<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2009-06-06
*/
class PageTest extends PageRecordsList
{
	/**
	 * cache var
	 * @var LBoxForm
	 */
	protected $form;
	
	/*protected function executePrepend(PHPTAL $TAL) {
		//DbControl::$debug = true;
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}*/
	
	/**
	 * @return LBoxForm
	 */
	public function getForm() {
		try {
			if ($this->form instanceof LBoxForm) {
				return $this->form;
			}
			
			$controls["text"]			= new LBoxFormControlFill("text", "text");
			$controls["text"]			->addFilter(new LBoxFormFilterTrim);
			$controls["text"]			->addValidator(new ValidatorURLParam);
			$controls["leaveempty"]		= new LBoxFormControlFill("leaveempty", "leave empty");
			$controls["leaveempty"]		->setRequired();
			
			$this->form	= new LBoxForm("test");
			foreach ($controls as $control) {
				$this->form->addControl($control);
			}
			$this->form->addProcessor(new LBoxFormProcessorDev);
			
			return $this->form;
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}
?>