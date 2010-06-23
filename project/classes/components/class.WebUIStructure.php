<?php
/**
 * Default component class used in case of no defined component class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2010-06-23
*/
class WebUIStructure extends WebUI
{
	/**
	 * pattern nazvu sablon typu stranek
	 * @var string
	 */
	protected $fileNamesTemplatePagesTypesPattern	= "^webui_(.+).html$";

	protected function executePrepend(PHPTAL $TAL) {
		try {
			parent::executePrepend($TAL);
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * cache var
	 * @var LBoxForm
	 */
	protected $form;

	/**
	 * getter na form editace stranky struktury
	 * @return LBoxForm
	 */
	public function getForm() {
		try {
			$controls["id"]			= new LBoxFormControlFillHidden("id", "", LBoxFront::getPage()->id);
			$controls["url"]		= new LBoxFormControlFill("url", "url", LBoxFront::getPage()->url);
			$controls["url"]		= new LBoxFormControlFill("url", "url", LBoxFront::getPage()->url);
				$controls["url"]		->addValidator(new WebUIFormControlValidatorStructureItemURL);
			$controls["type"]		= new LBoxFormControlChooseOne("type", "typ", $this->getPageTypeCurrent());
			foreach ($this->getPagesTypes() as $type) {
				$controls["type"]	->addOption(new LBoxFormControlOption($type, $type));
			}
			$controls["in_menu"]	= new LBoxFormControlChooseOne("in_menu", "v menu", LBoxFront::getPage()->in_menu);
			$controls["in_menu"]	->setTemplateFileName("lbox_form_control_choose_one_radio.html");
				$controls["in_menu"]	->addOption(new LBoxFormControlOption(1, "ano"));
				$controls["in_menu"]	->addOption(new LBoxFormControlOption(0, "ne"));
				
			$this->form = new LBoxForm("webui-structure-item-". LBoxFront:: getPage()->id, "post", "upravit stranku", "uložit");
			$this->form ->addProcessor(new LBoxFormProcessorDev());
			//$this->form ->addProcessor(new WebUIFormProcessorStructureItem());
			foreach($controls as $control) {
				$this->form->addControl($control);
			}
			return $this->form;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci typ current stranky
	 * @return string
	 */
	protected function getPageTypeCurrent() {
		try {
			if (!preg_match("/". $this->fileNamesTemplatePagesTypesPattern ."/", LBoxFront::getPage()->template)) {
				return NULL;
			}
			foreach ($this->getPagesTypes() as $type) {
				if (is_numeric(strpos(LBoxFront::getPage()->template, $type))) {
					return $type;
				}
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * cache var
	 * @var array
	 */
	protected $pagesTypes = array();

	/**
	 * vraci pole moznych typu stranek
	 * @return array
	 */
	protected function getPagesTypes() {
		try {
			if (count($this->pagesTypes) > 0) {
				return $this->pagesTypes;
			}
			foreach (LBoxUtil::getFilenamesOfDir(LBOX_PATH_TEMPLATES_PAGES) as $template) {
				if (preg_match("/". $this->fileNamesTemplatePagesTypesPattern ."/", $template, $matches)) {
					$this->pagesTypes[$template] = $matches[1];
				}
			}
			return $this->pagesTypes;
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}
?>