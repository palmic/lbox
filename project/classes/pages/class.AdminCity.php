<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-25
*/
class AdminCity extends PageDefault
{
	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
			
			$controls["name"]	= new LBoxFormControlFill("name", "název");
				$controls["name"]->setRequired();
				$controls["name"]->addFilter(new LBoxFormFilterName);
			$controls["ref_region"]	= new LBoxFormControlChooseOne("ref_region", "kraj");
				$controls["ref_region"]->setRequired();
			$regionsRecords	= new RegionsRecords(false, array("name" => 1));
			foreach ($regionsRecords as $region) {
				$controls["ref_region"]->addOption(new LBoxFormControlOption($region->id, $region->name));
			}
			
			$form	= new LBoxForm("city");
			foreach($controls as $control) {
				$form->addControl($control);
			}
			$form->addProcessor(new ProcessorAddCity());
			
			$TAL->form	= $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>