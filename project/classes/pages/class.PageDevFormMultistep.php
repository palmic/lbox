<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-11-23
*/
class PageDevFormMultistep extends PageDefault
{
	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);

			// controls
			$default	= "";
			$control1_1	= new LBoxFormControlFill($name = "control-1-1",  $label = "",  $default);
			$control1_2	= new LBoxFormControlFill($name = "control-1-2",  $label = "",  $default);
			$control2_1	= new LBoxFormControlFill($name = "control-2-1",  $label = "",  $default);
			$control2_2	= new LBoxFormControlFill($name = "control-2-2",  $label = "",  $default);

			$control1_1	->setTemplateFileName("lbox_form_control_fill_dev.html");
			$control1_2	->setTemplateFileName("lbox_form_control_fill_dev.html");
			$control2_1	->setTemplateFileName("lbox_form_control_fill_dev.html");
			$control2_2	->setTemplateFileName("lbox_form_control_fill_dev.html");
			
			// sub forms
			$formStep1	= new LBoxForm("form-step-1");
			$formStep2	= new LBoxForm("form-step-2");
			
			$formStep1	->addControl($control1_1);
			$formStep1	->addControl($control1_2);
			$formStep2	->addControl($control2_1);
			$formStep2	->addControl($control2_2);
			
			// global form
			$form	= new LBoxFormMultistep("form-multistep");
			$form	->addProcessor(new LBoxFormProcessorDevMultiple);
			$form	->addForm($formStep1);
			$form	->addForm($formStep2);
			
			/*$formStep1	->setDoNotReload();
			$formStep2	->setDoNotReload();
			$form		->setDoNotReload();*/
			
			$TAL->form	= $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>