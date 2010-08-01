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
			if ($this->form instanceof LBoxForm) {
				return $this->form;
			}
			if ($this->getPage() && $this->getPage()->config->isHomePage()) {
				return NULL;
			}
			$controls["id"]			= new LBoxFormControlFillHidden("id", "", $this->getPage() ? $this->getPage()->config->getParamDirect("id") : NULL);
			$controls["id"]			->setDisabled();
			$subControls["base"]["heading"]	= new LBoxFormControlFill("heading", "heading", $this->getPage() ? $this->getPage()->config->getParamDirect("heading") : LBoxUtil::getNameByURLString($this->getURLPartCurrentLast()));
				$subControls["base"]["heading"]	->setRequired();
				$subControls["base"]["heading"]	->setTemplateFileName("webui_structure_heading.html");
			$subControls["base"]["title"]		= new LBoxFormControlFill("title", "title", $this->getPage() ? $this->getPage()->config->getParamDirect("title") : NULL);
			$subControls["base"]["url"]		= new LBoxFormControlFill("url", "url", preg_replace("/(\/+)/", "", $this->getURLPartCurrentLast()));
				$subControls["base"]["url"]		->setRequired();
				$subControls["base"]["url"]		->addFilter(new LBoxFormFilterTrim);
				$subControls["base"]["url"]		->addFilter(new LBoxFormFilterURLStringFromName());
				$filterURLFromHeading	= new LBoxFormFilterURLFromName($subControls["base"]["url"]);
				$filterURLFromHeading	->setControlName($subControls["base"]["heading"]);
				$subControls["base"]["url"]		->addFilter($filterURLFromHeading);
				$subControls["base"]["url"]		->setTemplateFileName("webui_structure_url.html");
				$subControls["base"]["url"]		->addValidator(new WebUIFormControlValidatorStructureItemURL);
					$validatorPageExistsByURL	= new WebUIFormControlValidatorStructurePageExistsByURL($this->getPage() ? $this->getPage()->config->getParamDirect("id") : NULL);
					$subControls["base"]["url"]		->addValidator($validatorPageExistsByURL);
			if ((!$this->getPage()) || (preg_match("/". $this->fileNamesTemplatePagesTypesPattern ."/", $this->getPage()->config->template))) {
				$subControls["base"]["type"]		= new LBoxFormControlChooseOne("type", "typ", $this->getPageTypeCurrent());
					$subControls["base"]["type"]	->setRequired();
				foreach ($this->getPagesTypes() as $type) {
					$subControls["base"]["type"]	->addOption(new LBoxFormControlOption($type, $type));
				}
			}
			$subControls["structure"]["in_menu"]	= new LBoxFormControlChooseOne("in_menu", "v menu", $this->getPage() ? $this->getPage()->config->getParamDirect("in_menu") : 0);
				$subControls["structure"]["in_menu"]	->setRequired();
				foreach ($this->getOptionsInMenu() as $k=> $option) {
					$subControls["structure"]["in_menu"]	->addOption(new LBoxFormControlOption($k, $option));
				}
				if (count($this->getOptionsInMenu()) < 3) {
					$subControls["structure"]["in_menu"]	->setTemplateFileName("lbox_form_control_choose_one_radio.html");
				}
				else {
					$subControls["structure"]["in_menu"]	->setTemplateFileName("lbox_form_control_choose_one_select.html");
				}
			$subControls["structure"]["parent_id"]	= new LBoxFormControlChooseOne("parent_id", "parent", $this->getPage() && $this->getPage()->config->hasParent() ? $this->getPage()->config->getParent()->id : NULL);
			$subControls["structure"]["parent_id"]	->setTemplateFileName("lbox_form_control_choose_one_select.html");
				$this->fillControlChooseParentID($subControls["structure"]["parent_id"]);
			$subControls["structure"]["move_before"]= new LBoxFormControlChooseOne("move_before", "přesunout před", $this->getPage() ? $this->getValueCurrentMoveBefore() : NULL);
			$subControls["structure"]["move_before"]	->setTemplateFileName("lbox_form_control_choose_one_select.html");
				$this->fillControlChooseMoveBefore($subControls["structure"]["move_before"]);
			$subControls["seo"]["keywords"]		= new LBoxFormControlFill("keywords", "keywords", $this->getPage() ? $this->getPage()->config->getParamDirect("keywords") : NULL);
				$subControls["seo"]["keywords"]		->setTemplateFileName("webui_structure_keywords.html");
			$subControls["seo"]["description"]	= new LBoxFormControlFill("description", "description", $this->getPage() ? $this->getPage()->config->getParamDirect("description") : NULL);
				$subControls["seo"]["description"]	->setTemplateFileName("webui_structure_description.html");

			$validatorPageExistsByURL	->setControlParentID($subControls["structure"]["parent_id"]);
				
			foreach ($subControls as $themeName => $theme) {
				foreach ($theme as $subControl) {
					if (!array_key_exists($themeName, $controls) || !$controls[$themeName] instanceof LBoxFormControlMultiple) {
						$controls[$themeName]	= new LBoxFormControlMultiple($themeName);
						$controls[$themeName]	->setTemplateFileName("webui_structure_multi.html");
					}
					$controls[$themeName]	->addControl($subControl);
				}
			}
			$this->form	= new LBoxForm("webui-structure-item-". LBoxFront:: getPage()->id, "post", "upravit stranku", "uložit");
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
	 * @return LBoxPage
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
				$control->addOption(new LBoxFormControlOption(0, "&nbsp;"));
			}
			$iterator	= $root instanceof LBoxConfigItemStructure ? $root->getChildNodesIterator() : LBoxConfigManagerStructure::getInstance()->getIterator();
			foreach($iterator as $page) {
				if ($page->id == $this->getPage()->id) continue;
				$control->addOption(new LBoxFormControlOption($page->id, $pre . $page->heading ." - ". $page->url));
				if ($page->hasChildren()) {
					$this->fillControlChooseParentID($control, $page, "$pre&nbsp;&nbsp;&nbsp;&nbsp;");	
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
			return LBoxUtil::getURLByNameString($out);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * naplni control optiony
	 * @param LBoxFormControlChoose $control
	 * @param LBoxConfigItemStructure $root
	 * @param string $pre
	 */
	protected function fillControlChooseMoveBefore(LBoxFormControlChoose $control) {
		try {
			$iterator	= ($this->getPage() && $this->getPage()->config->hasParent())
								? $this->getPage()->config->getParent()->getChildNodesIterator()
								: LBoxConfigManagerStructure::getInstance()->getIterator();
			$control->addOption(new LBoxFormControlOption(0, "&nbsp;"));
			foreach($iterator as $page) {
				if ($page->id == $this->getPage()->id) continue;
				$control->addOption(new LBoxFormControlOption($page->id, $page->heading ." - ". $page->url));
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na hodnotu getMoveBefore aktualni stranky
	 * @return string
	 */
	protected function getValueCurrentMoveBefore() {
		try {
			return ($this->getPage() && $this->getPage()->config->getSiblingAfter())
								? $this->getPage()->config->getSiblingAfter()->id
								: NULL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * cache var
	 * @return array
	 */
	protected $optionsInMenu	= array();

	/**
	 * getter na veskere moznosti vyberu in_menu
	 * @return array
	 */
	protected function getOptionsInMenu() {
		try {
			if (count($this->optionsInMenu) > 0) {
				return $this->optionsInMenu;
			}
			$this->optionsInMenu[0]	= 0;
			$this->optionsInMenu[1]	= 1;
			$pagesIterator	= LBoxConfigManagerStructure::getInstance()->getIterator();
			foreach ($pagesIterator as $page) {
				$this->optionsInMenu[$page->in_menu]	= $page->in_menu;
			}
			switch (count($this->optionsInMenu)) {
				case 2:
						krsort($this->optionsInMenu);
						end($this->optionsInMenu);
						$this->optionsInMenu[key($this->optionsInMenu)]	= "ne";
						reset($this->optionsInMenu);
						$this->optionsInMenu[key($this->optionsInMenu)]	= "ano";
					break;
				default:
					$this->optionsInMenu[0]	= "v žádném menu";
					ksort($this->optionsInMenu);
			}
			return $this->optionsInMenu;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>