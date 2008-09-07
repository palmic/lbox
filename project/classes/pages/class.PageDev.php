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
			
			$control1	= new LBoxFormControlChooseOne	("test", "testovaci kontrol", 22);
			$control2	= new LBoxFormControlFill		("text");
			$control2	->setRequired();
			for ($i = 0; $i < 100; $i++) {
				$control1->addOption(new LBoxFormControlOption($i, "hodnota $i"));
			}
			$control1_2	= clone $control1;
			$control2_2	= clone $control2;
			
			$form		= new LBoxForm("test");
			$form		->addControl($control1);
			$form		->addControl($control2);
			$form		->addProcessor(new LBoxFormProcessorDev);
			$form->setAntiSpam();

			$form2		= new LBoxForm("test2");
			$form2		->addControl($control1_2);
			$form2		->addControl($control2_2);
			$form2		->addProcessor(new LBoxFormProcessorDev);
			$form2->setAntiSpam();
			
			$TAL->form	= $form;
			$TAL->form2	= $form2;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>