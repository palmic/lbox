<?php
class LBoxFormMultistep extends LBoxForm
{
	/**
	 * subforms array
 	 * @var array
	 */
	protected $subForms	= array();
	
	/**
	 * @var string
	 */
	protected $filenameTemplate = "lbox_form_multistep.html";

	/**
	 * @param string name
	 * @param string method
	 * @throws LBoxExceptionForm
	 */
	public function __construct( $name = "",  $method = "post", $label	= "", $labelSubmit	= "") {
		try {
			// jina metoda, nez post neakceptovana
			parent::__construct($name, "post", $label, $labelSubmit);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	public function __get($name = "") {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_PARAM_STRING_NOTNULL, LBoxExceptionForm::CODE_BAD_PARAM);
			}
			switch ($name) {
				case "form":
						return $this->getCurrentForm();
					break;
			}
			return parent::__get($name);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * prepina forms podle dokonceni jejich processingu a vraci vizualni obsah
	 * @return string
	 */
	public function __toString() {
		try {
			// pokus o vlastni processing na konci
			$this	->process();
			$out	= $this->getTAL()->execute();
			return $out;
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
			
			return $out;
		}
	}
	
	/**
	 * pridava formular na dalsi krok
	 * @param LBoxForm $form
	 */
	public function addForm(LBoxForm $form) {
		try {
			foreach ($this->subForms as $subForm) {
				if ($subForm->getName() == $form->getName()) {
					throw new LBoxExceptionForm(LBoxExceptionForm::MSG_FORM_FORM_SUB_ALREADY_SET_BY_NAME, LBoxExceptionForm::CODE_FORM_FORM_SUB_ALREADY_SET_BY_NAME);
				}
			}
			$this->subForms[count($this->subForms)+1]	= $form;
			$form->setFormMultistep($this);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function process() {
		try {
			// zkontrolovat, jestli mame nastaven procesor
			if (count($this->processors) < 1) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_FORM_PROCESSOR_DOESNOT_EXISTS, LBoxExceptionForm::CODE_FORM_PROCESSOR_DOESNOT_EXISTS);
			}
			$dataPost	= LBoxFront::getDataPost();
			if (strlen($dataPost[$this->getName()]["previous"]) > 0) {
				$this->moveToPreviousStep();
				LBoxFront::reload();
			}
			// pokud byl subform odeslan a uspesne zpracovan, posouvame se na dalsi krok
			$this->getCurrentForm()->__toString();
			if ($this->getCurrentForm()->wasSentSucces()) {
				$this->moveToNextStep();
			}
			if (!$this->wasFinishedSuccess()) {
				return;
			}
			foreach ($this->processors as $processor) {
				$processor->process();
			}
			// nastavit do session uspesne odeslani a reloadovat stranku
			if (!$this->doNotReload) {
				if (strtolower($this->method)	== "post") {
					$_SESSION["LBox"]["Forms"][$this->getName()]["step"]	= 1;
					$_SESSION["LBox"]["Forms"][$this->getName()]["steps"]	= array();
					$_SESSION["LBox"]["Forms"][$this->getName()]["succes"]	= true;
					LBoxFront::reload();
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na current form
	 * @return LBoxForm
	 */
	public function getCurrentForm() {
		try {
			if (!$this->subForms[$this->getStepCurrent()] instanceof LBoxForm) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_FORM_FORM_SUB_NOT_SET, LBoxExceptionForm::CODE_FORM_FORM_SUB_NOT_SET);
			}
			return $this->subForms[$this->getStepCurrent()];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci formular daneho kroku
	 * @param int $step
	 * @return LBoxForm
	 */
	public function getFormByStep($step = 1) {
		try {
			if (!array_key_exists($step, $this->subForms)) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_FORM_FORM_STEP_DOES_NOT_EXISTS,
											LBoxExceptionForm::CODE_FORM_FORM_STEP_DOES_NOT_EXISTS);
			}
			return $this->subForms[$step];
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na next form
	 * @return LBoxForm
	 */
	public function getNextForm() {
		try {
			return $this->subForms[$this->getStepCurrent()+1];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci true pokud byl multistep form v poradku dokoncen
	 * @return bool
	 */
	public function wasFinishedSuccess() {
		try {
			if ($this->subForms[$this->getStepCurrent()+1] instanceof LBoxForm) return false;
			return $this->getCurrentForm()->wasSentSucces();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * protected getter na current step
	 * @return int
	 */
	protected function getStepCurrent() {
		try {
			if ($_SESSION["LBox"]["Forms"][$this->getName()]["step"] < 1) {
				$_SESSION["LBox"]["Forms"][$this->getName()]["step"]	= 1;
			}
			return $_SESSION["LBox"]["Forms"][$this->getName()]["step"];
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * posouva o jeden krok dal, pokud nasledujici krok existuje
	 */
	protected function moveToNextStep() {
		try {
			// save current form data
			foreach ($this->getCurrentForm()->getControls() as $control) {
				$_SESSION["LBox"]["Forms"][$this->getName()]["steps"]
					[$this->getStepCurrent()]["data"][$control->getName()]	= $control->getValue();
			}
			if (!$this->subForms[$this->getStepCurrent()+1] instanceof LBoxForm) {
				return;
			}
			$_SESSION["LBox"]["Forms"][$this->getName()]["step"]	= $this->getStepCurrent()+1;
			LBoxFront::reload();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * posouva o jeden krok zpet, pokud predchozi krok existuje
	 */
	protected function moveToPreviousStep() {
		try {
			if (!$this->subForms[$this->getStepCurrent()-1] instanceof LBoxForm) {
				return;
			}
			$_SESSION["LBox"]["Forms"][$this->getName()]["step"]	= $this->getStepCurrent()-1;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na subForms
	 * @return array
	 */
	public function getFormsSub() {
		try {
			return $this->subForms;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci veskera formularova data z current formulare
	 * @return array
	 */
	public function getFormsDataCurrentStep() {
		try {
			return $this->getFormsDataStep($this->getStepCurrent(), true);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci formularova data konkretniho kroku
	 * @param int $step
	 * @return array
	 */
	public function getFormsDataStep($step = 0) {
		try {
			$formsData	= $this->getFormsData();
			return $formsData[$step];
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci formularova data konkretniho kroku podle predaneho formu
	 * @param LBoxForm $form
	 * @return array
	 */
	public function getFormsDataStepByForm(LBoxForm $form) {
		try {
			$step = NULL;
			foreach ($this->subForms as $stepSubform => $subForm) {
				if ($form->getName() == $subForm->getName()) {
					$step	= $stepSubform;
				}
			}
			if (!is_int($step)) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_FORM_FORM_SUB_NOT_SET, LBoxExceptionForm::CODE_FORM_FORM_SUB_NOT_SET);
			}
			
			$formsData	= $this->getFormsData();
			return $formsData[$step];
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci veskera formularova data ze vsech dosud odeslanych formularu
	 * @return array
	 */
	public function getFormsData() {
		try {
			$out = array();
			foreach ((array)$_SESSION["LBox"]["Forms"][$this->getName()]["steps"] as $step => $dataSession) {
				$out[$step]	= $dataSession["data"];
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci data konkretniho control v konkretnim kroku
	 * @return array
	 */
	public function getFormsDataStepControl($step = 0, $controlName = "") {
		try {
			if (strlen($controlName) < 1) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_PARAM_STRING_NOTNULL, LBoxExceptionForm::CODE_BAD_PARAM);
			}
			$stepData	= (array)$this->getFormsDataStep($step);
			return $stepData[$controlName];
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>