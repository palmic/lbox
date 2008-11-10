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
			
			$form		= new LBoxForm("test-form", "post");
$form->setAntiSpam(true);
			$control1	= new LBoxFormControlFill("text-1", "prvni textove pole", "defaultni hodnota");
$control1->setRequired();
			$form->addControl($control1);
			$form->addProcessor(new LBoxFormProcessorDev);
			
			$TAL->form	= $form;
			/*var_dump($form);
			die($form);*/
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>