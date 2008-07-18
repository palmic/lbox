<?php
/**
 * class LBoxForm
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
	 *
	 * @param string name 
	 * @param string method
	 * @throws LBoxExceptionForm
	 */
	public function __construct( $name = "",  $method = "get" ) {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionForm("\$name: ". LBoxExceptionForm::MSG_PARAM_STRING_NOTNULL, LBoxExceptionForm::CODE_BAD_PARAM);
			}
			if (strlen($method) < 1) {
				throw new LBoxExceptionForm("\$method: ". LBoxExceptionForm::MSG_PARAM_STRING_NOTNULL, LBoxExceptionForm::CODE_BAD_PARAM);
			}
			$this->name		= $name;
			$this->method	= $method;
			if (array_key_exists($name, self::$forms)) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_FORM_DUPLICATE_FORMNAME, LBoxExceptionForm::CODE_FORM_DUPLICATE_FORMNAME);
			}
			self::$forms[$name]	= $this;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @param LBoxFormControl $control
	 * @throws LBoxExceptionForm
	 */
	public function addControl(LBoxFormControl $control = NULL) {
		try {
			if (array_key_exists($control->getName(), $this->controls)) {
				throw new LBoxExceptionForm($control->getName() .": ", LBoxExceptionForm::MSG_FORM_CONTROL_DOES_EXISTS, LBoxExceptionForm::CODE_FORM_CONTROL_DOES_EXISTS);
			}
			$this->controls[$control->getName()]	= $control;
			$control->setForm($this);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @param LBoxFormProcessor processor 
	 * @throws LBoxExceptionForm
	 */
	public function addProcessor(LBoxFormProcessor $processor = NULL) {
		try {
			if (array_key_exists(get_class($processor), $this->processors)) {
				throw new LBoxExceptionForm($processor->getName() .": ", LBoxExceptionForm::MSG_FORM_PROCESSOR_DOES_EXISTS, LBoxExceptionForm::CODE_FORM_PROCESSOR_DOES_EXISTS);
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
			if (!array_key_exists($name, $this->controls)) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_FORM_CONTROL_DOESNOT_EXISTS, LBoxExceptionForm::CODE_FORM_CONTROL_DOESNOT_EXISTS);
			}
			$sentData	= $this->getSentData();
			return $sentData["$name"];
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
			return $data[$this->name];
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
	 *
	 * @return string
	 */
	public function __toString() {
		try {
			// pridat antispam, pokud je zapnut
			if ($this->isAntiSpamSet()) {
				$this->controls[$this->getSpamDefenseControl()->getName()]	= $this->getSpamDefenseControl();
			}
			if ($this->wasSent()) {
				
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
	public function setAntiSpam($antiSpamSet	= false) {
		try {
			$this->antiSpamSet	= $antiSpamSet;
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
	 * @throws LBoxExceptionForm
	 */
	protected function process() {
		try {
			if (!$this->wasSent()) return;
			// zkontrolovat, jestli mame nastaven procesor
			if (count($this->processors) < 1) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_FORM_PROCESSOR_DOESNOT_EXISTS, LBoxExceptionForm::CODE_FORM_PROCESSOR_DOESNOT_EXISTS);
			}
			// spustit checking controls
			foreach ($this->controls as $control) {
				$control->process();
			}
			// spustit processory
			foreach ($this->processors as $processor) {
				$processor->process();
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
	 * @return LBoxFormControlFillHidden
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
}
?>