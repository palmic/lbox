<?php
/**
 * class LBoxForm
* @author Michal Palma <palmic at gmail dot com>
* @package LBox
 */
class LBoxForm
{
	/**
	 * pole veskerych vytvorenych formularu pro kontroly mezi nimi (hlavne co se tyce unikatnosti nazvu)
	 * @var array
	 */
	protected static $forms	= array();

	/**
	 * @var array
	 */
	protected $controls = array();

	/**
	 * @var array
	 */
	protected $processors = array();

	/**
	 * @var string
	 */
	protected $filenameTemplate = "lbox_form.html";

	/**
	 * @var PHPTAL
	 */
	protected $TAL;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var string
	 */
	protected $labelSubmit;

	/**
	 * @var string
	 */
	protected $method;

	/**
	 * antispam set flag
	 * @var bool
	 */
	protected $antiSpamSet	= false;

	/**
	 * method cache variable
	 * @var LBoxFormControlFillHidden
	 */
	protected $spamDefenseControl;

	/**
	 * sent succes flag - po odeslani formulare a reloadu stranky by tato promena by mela byt true
	 * @var bool
	 */
	protected $sentSucces	= false;
	
	/**
	 * do not reload after succcesfull processing flag
	 * @var bool
	 */
	protected $doNotReload	= false;

	/**
	 * cache metody getFilesData
	 * @var array
	 */
	protected $filesData	= array();

	/**
	 * pole volne definovatelnych parametru
	 * @var array
	 */
	protected $params	= array();
	
	/**
	 * master formmultistep if this is subform
	 * @var LBoxFormMultistep
	 */
	protected $formMultistep	= false;
	
	/**
	 *
	 * @param string name
	 * @param string method
	 * @throws LBoxExceptionForm
	 */
	public function __construct($name = "",  $method = "post", $label	= "", $labelSubmit	= "") {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionForm("\$name: ". LBoxExceptionForm::MSG_PARAM_STRING_NOTNULL, LBoxExceptionForm::CODE_BAD_PARAM);
			}
			if (strlen($method) < 1) {
				throw new LBoxExceptionForm("\$method: ". LBoxExceptionForm::MSG_PARAM_STRING_NOTNULL, LBoxExceptionForm::CODE_BAD_PARAM);
			}
			$this->name			= $name;
			$this->method		= strtolower($method);
			$this->label		= strlen($label) 		> 0 ? $label 		: $name;
			$this->labelSubmit	= strlen($labelSubmit) 	> 0 ? $labelSubmit 	: "odeslat";
			if (array_key_exists($name, self::$forms)) {
				throw new LBoxExceptionForm("$name: ". LBoxExceptionForm::MSG_FORM_DUPLICATE_FORMNAME, LBoxExceptionForm::CODE_FORM_DUPLICATE_FORMNAME);
			}
			self::$forms[$name]	= $this;
			if (array_key_exists("LBox", (array)$_SESSION))
			if ($_SESSION["LBox"]["Forms"][$this->getName()]["succes"]) {
				unset($_SESSION["LBox"]["Forms"][$this->getName()]["succes"]);
				$this->sentSucces	= true;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * defaultni getter
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name = "") {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_PARAM_STRING_NOTNULL, LBoxExceptionForm::CODE_BAD_PARAM);
			}
			if (array_key_exists($name, $this->params)) {
				return $this->params[$name];
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * setter pro params
	 * @param string $name
	 * @param mixed $value
	 * @throws LBoxException
	 */
	public function __set($name = "", $value = NULL) {
		if (strlen($name) < 1) {
			throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormControl::CODE_BAD_PARAM);
		}
		if (is_null($value)) {
			throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormControl::CODE_BAD_PARAM);
		}
		$this->params[$name]	= $value;
	}
	
	/**
	 * @param LBoxFormControl $control
	 * @throws LBoxExceptionForm
	 */
	public function addControl(LBoxFormControl $control) {
		try {
			if (array_key_exists($control->getName(), $this->controls)) {
				throw new LBoxExceptionForm($control->getName() .": ". LBoxExceptionForm::MSG_FORM_CONTROL_DOES_EXISTS, LBoxExceptionForm::CODE_FORM_CONTROL_DOES_EXISTS);
			}
			// z multistep control prebereme jeho subcontrols
			if ($control instanceof LBoxFormControlMultiple) {
				foreach ($control->getControls() as $subControl) {
					if (!array_key_exists($subControl->getName(), $this->controls)) {
						$this->controls[$subControl->getName()]	= $subControl;
					}
				}
			}
			if (strtolower($control->getName()) == "previous") {
				throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_FORM_CONTROL_NAME_FORBIDDEN, LBoxExceptionFormControl::CODE_FORM_CONTROL_NAME_FORBIDDEN);
			}
			$this->controls[$control->getName()]	= $control;
			$control->setForm($this);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @param LBoxFormProcessor processor
	 * @throws LBoxExceptionForm
	 */
	public function addProcessor(LBoxFormProcessor $processor) {
		try {
			if (array_key_exists(get_class($processor), $this->processors)) {
				throw new LBoxExceptionForm(get_class($processor) .": ". LBoxExceptionForm::MSG_FORM_PROCESSOR_DOES_EXISTS, LBoxExceptionForm::CODE_FORM_PROCESSOR_DOES_EXISTS);
			}
			$this->processors[get_class($processor)]	= $processor;
			$processor->setForm($this);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @param string name - control name to return sent data
	 * @return array
	 * @throws LBoxExceptionForm
	 */
	public function getSentDataByControlName($name = "") {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_PARAM_STRING_NOTNULL, LBoxExceptionForm::CODE_BAD_PARAM);
			}
			// prohledat controls
			if (!array_key_exists($name, $this->controls)) {
				throw new LBoxExceptionForm("\$name: ". LBoxExceptionForm::MSG_FORM_CONTROL_DOESNOT_EXISTS, LBoxExceptionForm::CODE_FORM_CONTROL_DOESNOT_EXISTS);
			}
			$sentData	= $this->getSentData();
			return $sentData["$name"];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci control podle jmena
	 * @param string $name
	 * @return LBoxFormControl
	 */
	public function getControlByName($name = "") {
		try {
			$name	= strtolower($name);
			if (!array_key_exists($name, $this->controls)) {
				throw new LBoxExceptionForm("$name: ". LBoxExceptionForm::MSG_FORM_CONTROL_DOESNOT_EXISTS, LBoxExceptionForm::CODE_FORM_CONTROL_DOESNOT_EXISTS);
			}
			return $this->controls[$name];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return array
	 */
	public function getSentData() {
		try {
			$data	= array();
			switch (strtolower($this->method)) {
				case "get":
					$data	= LBoxFront::getDataGet();
					break;
				case "post":
					$data	= LBoxFront::getDataPost();
					break;
			}
			// pridame filesdata, pokud nejaka jsou
			if (count($this->getFilesData()) > 0) {
				$filesData	= $this->getFilesData();
				$data[$this->getName()]	= array_merge($data[$this->getName()], $filesData[$this->getName()]);
			}
			return array_key_exists($this->name, $data) ? $data[$this->name] : NULL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Tridi a vraci odeslana FILES data
	 * @return array
	 */
	protected function getFilesData() {
		try {
			if (count($this->filesData) > 0) {
				return $this->filesData;
			}
			$data	= LBoxFront::getDataFiles();
			$out	= array();
			// pretridime data podle files controls
			if (array_key_exists($this->getName(), (array)$data))
			foreach ((array)$data[$this->getName()]["name"] as $controlName	=> $fileName) {
				foreach ($data[$this->getName()] as $paramName	=> $params) {
					$out[$this->getName()][$controlName][$paramName]	= $params[$controlName];
				}
			}
			return $this->filesData	= $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return bool
	 */
	public function wasSent() {
		try {
			return (count($this->getSentData()) > 0);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @return string
	 */
	public function getMethod() {
		try {
			return strtolower($this->method);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @return string
	 */
	public function getName() {
		try {
			return $this->name;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		try {
			return $this->label;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return string
	 */
	public function getLabelSubmit() {
		try {
			return $this->labelSubmit;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @return array
	 */
	public function getControls() {
		try {
			return $this->controls;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci control spam defense
	 * @return LBoxFormControlSpamDefense
	 */
	public function getControlSpamDefense() {
		try {
			foreach ($this->controls as $control) {
				if ($control->isSpamDefense()) {
					return $control;
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci celou cestu k sablone control (ovlivnenou atributem filenameTemplate instance control)
	 * @return string
	 */
	protected function getPathTemplate() {
		try {
			$pathTemplatesForms	= LBoxConfigSystem::getInstance()->getParamByPath("forms/templates/forms/path");
			return "$pathTemplatesForms/". $this->filenameTemplate;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * k nastaveni zvlastni sablony
	 * @param string $filename
	 */
	public function setTemplateFileName($filename = "") {
		try {
			if (strlen($filename) < 1) {
				throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormControl::CODE_BAD_PARAM);
			}
			$this->filenameTemplate	= $filename;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		try {
			// pridat antispam, pokud je zapnut
			if ($this->isAntiSpamSet()) {
				$this->controls[$this->getSpamDefenseControl()->getName()]	= $this->getSpamDefenseControl();
			}
			try {
				$out 	 = "";
				$this->process();
				$out	.= $this->getTAL()->execute();
			}
			catch (Exception $e) {
				// var_dump($e);
				switch (get_class($e)) {
					case "LBoxFormProcessor":
						//TODO
						NULL;
						break;
				}
				$out 	 = "";
				$out	.= "PHPTAL Exception thrown";
				$out	.= "\n";
				$out	.= "\n";
				$out	.= "code: ". nl2br($e->getCode()) ."";
				$out	.= "\n";
				$out	.= "message: ". nl2br($e->getMessage()) ."";
				$out	.= "\n";
				$out	.= "Thrown by: '". $e->getFile() ."'";
				$out	.= "\n";
				$out	.= "on line: '". $e->getLine() ."'.";
				$out	.= "\n";
				$out	.= "\n";
				$out	.= "Stack trace:";
				$out	.= "\n";
				$out	.= nl2br($e->getTraceAsString());
				// $out 	= nl2br($out) ."<hr />\n\n";
				$out 	= "<!--$out-->";
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * prepina antispam kontrolu
	 * @param bool $antiSpamSet
	 */
	public function setAntiSpam($antiSpamSet	= true) {
		try {
			$this->antiSpamSet	= $antiSpamSet;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * prepina doNotReload kontrolu
	 * @param bool $value
	 */
	public function setDoNotReload($value	= true) {
		try {
			$this->doNotReload	= $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci, jestli je zapnut antispam
	 * @return bool
	 */
	public function isAntiSpamSet() {
		try {
			return $this->antiSpamSet;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci, jestli byl formular vporadku odeslan
	 * @return bool
	 */
	public function wasSentSucces() {
		try {
			return $this->sentSucces;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @throws LBoxExceptionForm
	 */
	protected function process() {
		try {
			if (!$this->wasSent()) return;
			// zkontrolovat, jestli mame nastaven procesor
			if ((!$this->isSubForm()) && count($this->processors) < 1) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_FORM_PROCESSOR_DOESNOT_EXISTS, LBoxExceptionForm::CODE_FORM_PROCESSOR_DOESNOT_EXISTS);
			}
			if ($this->isAntiSpamSet()) {
				if (!$this->getSpamDefenseControl()->process()) {
					return;
				}
			}
			// spustit checking controls
			$controlInvalid	= false;
			foreach ($this->controls as $control) {
				if ($control->getName() == $this->getSpamDefenseControl()->getName()) {
					continue;
				}
				if (!$control->process()) {
					$controlInvalid	= true;
				}
			}
			if ($controlInvalid) return;
			// spustit processory
			foreach ($this->processors as $processor) {
				$processor->process();
			}
			// oznamit controls uspesne dokonceny processing
			foreach ($this->controls as $control) {
				$control->commitProcessSuccess();
			}
			// nastavit do session uspesne odeslani a reloadovat stranku
			if (!$this->doNotReload) {
				if (strtolower($this->method)	== "post") {
					$this->sentSucces	= true;
					$_SESSION["LBox"]["Forms"][$this->getName()]["succes"]	= true;
					if (!$this->isSubForm()) {
						LBoxFront::reload();
					}
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @return PHPTAL
	 */
	protected function getTAL() {
		try {
			if (!$this->TAL instanceof PHPTAL) {
				$this->TAL = new PHPTAL($this->getPathTemplate());
			}
			$this->TAL->SELF = $this;
			return $this->TAL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci control pro spamdefense
	 * @return LBoxFormControlSpamDefense
	 */
	protected function getSpamDefenseControl() {
		try {
			if ($this->spamDefenseControl instanceof LBoxFormControl) {
				return $this->spamDefenseControl;
			}
			$this->spamDefenseControl	= new LBoxFormControlSpamDefense();
			$this->spamDefenseControl->setForm($this);
			return $this->spamDefenseControl;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * @param LBoxFormMultistep $form
	 */
	public function setFormMultistep(LBoxFormMultistep $form) {
		$this->formMultistep	= $form;
		$this->method		= "post";
	}

	/**
	 * @return LBoxFormMultistep
	 */
	public function getFormMultistep() {
		return $this->formMultistep;
	}
	
	/**
	 * subform flag getter
	 * @return bool
	 */
	public function isSubForm() {
		return ($this->formMultistep instanceof LBoxForm);
	}
	
	/**
	 * @return bool
	 */
	public function isFirstSubForm() {
		if (!$this->isSubForm()) {
			return false;
		}
		return (reset($this->getFormMultistep()->getFormsSub())->getName() == $this->getName());
	}
	
	/**
	 * @return bool
	 */
	public function isLastSubForm() {
		if (!$this->isSubForm()) {
			return false;
		}
		return (end($this->getFormMultistep()->getFormsSub())->getName() == $this->getName());
	}
}
?>