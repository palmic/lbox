<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-18
*/
class PageDev extends PageDefault
{
	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
			
			$control	= new LBoxFormControlFill("test");
			$form		= new LBoxForm("test");
			$form		->addControl($control);
			$form		->addProcessor(new LBoxFormProcessorDev);
			$form->setAntiSpam();
			
			$TAL->form	= $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>