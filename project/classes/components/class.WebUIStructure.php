<?php
/**
 * Zobrazuje form pro editaci stranky ve structure.xml
 * @author michal.palma@gmail.com
 * @since 2010-06-24
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
			$controls["id"]			= new LBoxFormControlFillHidden("id", "", $this->getPage() ? $this->getPage()->config->getParamDirect("id") : NULL);
			$controls["id"]			->setDisabled();
			$controls["heading"]	= new LBoxFormControlFill("heading", "heading", $this->getPage() ? $this->getPage()->config->getParamDirect("heading") : NULL);
				$controls["heading"]	->setRequired();
			$controls["title"]		= new LBoxFormControlFill("title", "title", $this->getPage() ? $this->getPage()->config->getParamDirect("title") : NULL);
			$controls["url"]		= new LBoxFormControlFill("url", "url", preg_replace("/(\/+)/", "", $this->getPage() ? $this->getPage()->config->getParamDirect("url") : $this->getURLPartCurrentLast()));
				$controls["url"]		->setRequired();
				$controls["url"]		->addFilter(new LBoxFormFilterTrim);
				$controls["url"]		->addFilter(new LBoxFormFilterURLStringFromName());
				$controls["url"]		->addValidator(new WebUIFormControlValidatorStructureItemURL);
				$validatorURLFromHeading	= new LBoxFormFilterURLFromName($controls["url"]);
				$validatorURLFromHeading	->setControlName($controls["heading"]);
				$controls["url"]		->addFilter($validatorURLFromHeading);
			$controls["parent_id"]	= new LBoxFormControlChooseOne("parent_id", "parent", $this->getPage() ? $this->getPage()->config->getParent()->id : NULL);
			$controls["parent_id"]	->setTemplateFileName("lbox_form_control_choose_one_select.html");
				$this->fillControlChooseParentID($controls["parent_id"]);
			$controls["type"]		= new LBoxFormControlChooseOne("type", "typ", $this->getPageTypeCurrent());
				$controls["type"]	->setRequired();
			foreach ($this->getPagesTypes() as $type) {
				$controls["type"]	->addOption(new LBoxFormControlOption($type, $type));
			}
			$controls["in_menu"]	= new LBoxFormControlChooseOne("in_menu", "v menu", $this->getPage() ? $this->getPage()->config->getParamDirect("in_menu") : 0);
			$controls["in_menu"]	->setTemplateFileName("lbox_form_control_choose_one_radio.html");
				$controls["in_menu"]	->setRequired();
				$controls["in_menu"]	->addOption(new LBoxFormControlOption(1, "ano"));
				$controls["in_menu"]	->addOption(new LBoxFormControlOption(0, "ne"));

			$this->form = new LBoxForm("webui-structure-item-". LBoxFront:: getPage()->id, "post", "upravit stranku", "uložit");
			$processor	= new WebUIFormProcessorStructureItem();
			$processor	->setFileNamesTemplatePagesTypesPattern($this->fileNamesTemplatePagesTypesPattern);
			//$this->form ->addProcessor(new LBoxFormProcessorDev());
			$this->form ->addProcessor($processor);
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
			if (!$this->getPage()) {
				return NULL;
			}
			if (!preg_match("/". $this->fileNamesTemplatePagesTypesPattern ."/", $this->getPage()->template)) {
				return NULL;
			}
			foreach ($this->getPagesTypes() as $type) {
				if (is_numeric(strpos($this->getPage()->template, $type))) {
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
	 * Vraci aktualni stranku, nebo NULL pokud jde o 404
	 * @return LBoxConfigItemStructure
	 */
	protected function getPage() {
		try {
			// pokud je aktualni stranka 404, tak vracet NULL, aby byl formular nastaven na vytvoreni nove stranky
			return LBoxFront::getPage()->id == LBoxConfigSystem::getInstance()->getParamByPath("pages/page404") ? NULL : LBoxFront::getPage();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
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

	/**
	 * naplni control optiony
	 * @param LBoxFormControlChoose $control
	 * @param LBoxConfigItemStructure $root
	 * @param string $pre
	 */
	protected function fillControlChooseParentID(LBoxFormControlChoose $control, LBoxConfigItemStructure $root = NULL, $pre = "") {
		try {
			if (!$root) {
				$control->addOption(new LBoxFormControlOption(0, " "));
			}
			$iterator	= $root instanceof LBoxConfigItemStructure ? $root->getChildNodesIterator() : LBoxConfigManagerStructure::getInstance()->getIterator();
			foreach($iterator as $page) {
				if ($page->id == $this->getPage()->id) continue;
				$control->addOption(new LBoxFormControlOption($page->id, $pre . $page->url ." - ". $page->heading));
				if ($page->hasChildren()) {
					$this->fillControlChooseParentID($control, $page, "$pre  ");
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci posledni cast aktualni URL
	 * @return string
	 */
	protected function getURLPartCurrentLast() {
		try {
			$urlParts	= explode("/", LBOX_REQUEST_URL_VIRTUAL);
			foreach ($urlParts as $part) {
				if (strlen(trim($part)) > 0) {
					$out = $part;
				}
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>