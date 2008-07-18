<?php
/**
 * class LBoxFormControl
 */
abstract class LBoxFormControl
{
	/**
	 * @var array
	 */
	protected $validators = array();

	/**
	 * @var array
	 */
	protected $filters = array();

	/**
	 * @var PHPTAL
	 */
	protected $TAL;

	/**
	 * nadrazeny formular
	 * @var LBoxForm
	 */
	protected $form;
	
	/**
	 * nazev - ovlivnuje sent data (post/get)
	 * @var string
	 */
	protected $name		= "";
	
	/**
	 * label
	 * @var string
	 */
	protected $label	= "";
	
	/**
	 * defaultni hodnota
	 * @var string
	 */
	protected $default	= "";
	
	/**
	 * form processed flag
	 * @var bool
	 */
	protected $processed	= false;
	
	/**
	 * hodnota pokud byl formular odeslan
	 * @var string
	 */
	protected $value;
	
	/**
	 *
	 * @param string name 
	 * @param string label 
	 * @param string default defaultni hodnota ovladaciho prvku
	 * @throws LBoxExceptionFormControl
	 */
	public function __construct($name = "",  $label = "",  $default = "") {
		try {
			if (strlen($name) < 0) {
				throw new LBoxExceptionFormControl("\$name: ". LBoxExceptionFormControl::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormControl::CODE_BAD_PARAM);
			}
			if (strlen($label) < 0) {
				throw new LBoxExceptionFormControl("\$label: ". LBoxExceptionFormControl::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormControl::CODE_BAD_PARAM);
			}
			$this->name		= $name;
			$this->label	= $label;
			$this->default	= $default;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @param LBoxFormValidator validator
	 */
	public function addValidator(LBoxFormValidator $validator = NULL ) {
		try {
			$this->validators[]	= $validator;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @param LBoxFormFilter filter 
	 */
	public function addFilter(LBoxFormFilter $filter = NULL) {
		try {
			$this->filters[]	= $filter;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * sets control required
	 */
	public function setRequired() {
		try {
			$this->addValidator(new LBoxFormValidatorRequired);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * spousti validace a filtering
	 * @throws LBoxExceptionForm
	 */
	public function process() {
		try {
			if ($this->processed) {
				return;
			}
			// zajistit nastaveni hodnoty
			$this->getValue();
			foreach ($this->filters as $filter) {
				if (($filteredValue	= $filter->filter($this)) === NULL) {
					throw new LBoxExceptionFormControl(LBoxExceptionFormControl::MSG_FORM_FILTER_OUT_VALUE_NULL, LBoxExceptionFormControl::CODE_FORM_FILTER_OUT_VALUE_NULL);
				}
				$this->value	= $filteredValue;
			}
			foreach ($this->validators as $validator) {
				$validator->validate($this);
			}
			$this->processed	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Jen pro pouziti z instance LBoxForm, nepouzivat zvenku!
	 * @param LBoxForm form 
	 */
	public function setForm($form = NULL) {
		try {
			$this->form	= $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return string
	 *
	 */
	public function __toString() {
		try {
			try {
				$out 	 = "";
				$out	.= $this->getTAL()->execute();
			}
			catch (Exception $e) {
				// var_dump($e);

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
	 * vraci celou cestu k sablone control (ovlivnenou atributem filenameTemplate instance control)
	 * @return string
	 */
	protected function getPathTemplate() {
		try {
			$pathTemplatesForms	= LBoxConfigSystem::getInstance()->getParamByPath("forms/templates/controls/path");
			return "$pathTemplatesForms/". $this->filenameTemplate;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci sve jmeno
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
	 * vraci svou hodnotu zadanou uzivatelem (napriklad post data)
	 * @return string
	 */
	public function getValue() {
		try {
			if ($this->value !== NULL) {
				return $value;
			}
			return $this->value	= $this->form->getSentDataByControlName($this->name);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @return PHPTAL
	 */
	protected function getTAL( ) {
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
}
?>