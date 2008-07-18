<?
/**
 * formular contact us
 * @author Michal Palma <palmic@email.cz>
 * @package LBox
 * @version 1.0

 * @since 2008-03-23
 */
class ContactForm extends LBoxComponent
{
	protected function executeStart() {
		try {
			$this->config->setOutputFilter(new OutputFilterContactForm($this->config));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function executePrepend(PHPTAL $TAL) {
		// DbControl::$debug = true;
		try {
			$TAL->form				= $this->getFormArray();
			if ($_SESSION[$this->getFormGroupName()]["send-reload"]) {
				$TAL->sendReload	= true;
				unset($_SESSION[$this->getFormGroupName()]);
			}
			try {
				$this->saveData();
			}
			catch (LBoxExceptionForm $eF) {
				$TAL->form	= $eF->getFormArray();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Prekontroluje data, pokud byla odeslana a upravena je vraci
	 * V pripade chyby vyhozuje vyjimku, ktera obsahuje i kompletni data formu s oznacenymi chybami
	 * @return array
	 * @throws LBoxExceptionForm
	 */
	protected function checkData() {
		try {
			$formGroupName			= $this->getFormGroupName();
			if (count($_POST[$formGroupName]) < 1) {
				return;
			}

			$error 		= false;
			$formArray	= $this->getFormArray();

			$controls	= $formArray["controls"];

			// kontroly
			foreach ($controls as $name => $control) {
				// required
				if ($control["required"]) {
					if (strlen(strip_tags($control["value"])) < 1) {
						$control["error"]["empty"] = true;
						$error = true;
					}
				}
				// maxlength
				if (is_numeric($control["maxLength"]))
				if (strlen($control["value"]) > $control["maxLength"]) {
					$control["error"]["tooLong"] = true;
					$error = true;
				}
				// kontrola detailni
				if (!$error)
				switch ($name) {
					case "email":
						if (strlen($control["value"]))
						if (!eregi("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $control["value"])) {
							$control["error"]["invalid"] = true;
							$error = true;
						}
						break;
				}
				$controls[$name] = $control;
			}
			$formArray["controls"] = $controls;
			if ($error) {
				$exception = new LBoxExceptionForm(LBoxExceptionForm::MSG_FORM_DATA_INVALID, LBoxExceptionForm::CODE_FORM_DATA_INVALID);
				$exception->setFormArray($formArray);
				throw $exception;
			}
			else {
				// upravy
				foreach ($controls as $name => $control) {
					switch ($name) {
						case "email":
						break;
					}
					$controls[$name] = $control;
				}
			}
			$formArray["controls"] = $controls;
			return $formArray;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Odesle mail, pokud byl odeslan
	 * @throws Exception
	 */
	protected function saveData() {
		try {
			$formGroupName			= $this->getFormGroupName();
			if (count($_POST[$formGroupName]) < 1) {
				return;
			}
			// ziskame upravena, zkontrolovana data formulare
			$formArray = $this->checkData();

			$sendersName	= trim($formArray["controls"]["name"]["value"]);
			$sendersEmail	= trim($formArray["controls"]["email"]["value"]);
			$message		= trim($formArray["controls"]["text"]["value"]);
			$subject 		= "E-mail od navstevnika webu '". LBOX_REQUEST_URL_HOST ."'";

			$headers = "Content-Type: text/plain; charset=UTF-8\n";
			$headers .= "From: ".$sendersName."<".$sendersEmail.">\n";
			//$headers .= "Return-Path: ".$sendersEmail."\n";
			
			$addresses	= $this->contact_form_addresses;
			if (!mail($addresses, $subject, $message, $headers)) {
				throw new LBoxExceptionComponent("Cannot send e-mail!");
			}
			$_SESSION[$this->getFormGroupName()]["send-reload"] = true;
			$this->reload();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci pole s daty formulare a dalsimi vecmi
	 * @return array
	 */
	protected function getFormArray() {
		try {
			$formGroupName			= $this->getFormGroupName();
			$form 					= array();
			$controls				= array();

			// id
			$controls["name"]["id"]					= $formGroupName. "-name";
			$controls["email"]["id"]				= $formGroupName. "-email";
			$controls["text"]["id"]					= $formGroupName. "-text";
				
			// groupName
			$controls["name"]["name"]				= $formGroupName. "[name]";
			$controls["email"]["name"]				= $formGroupName. "[email]";
			$controls["text"]["name"]				= $formGroupName. "[text]";
							
			// required
			$controls["name"]["required"]			= true;
			$controls["email"]["required"]			= true;
			$controls["text"]["required"]			= true;
							
			// max length
			$controls["name"]["maxLength"]			= LBoxConfigManagerProperties::getInstance()->getPropertyByName("form_max_length_name")->getContent();
			$controls["email"]["maxLength"]			= LBoxConfigManagerProperties::getInstance()->getPropertyByName("form_max_length_email")->getContent();
			$controls["text"]["maxLength"]			= 999999;
			
			// data - pokud neexistuji postdata, budou tam prazdne stringy
			if (LBoxXT::isLogged()) {
				$values["name"]		= strlen($_POST[$formGroupName]["name"]) > 0 ? $_POST[$formGroupName]["name"] : LBoxXT::getUserXTRecord()->name ." ". LBoxXT::getUserXTRecord()->surname;
				$values["email"]	= strlen($_POST[$formGroupName]["email"]) > 0 ? $_POST[$formGroupName]["email"] : LBoxXT::getUserXTRecord()->email;
			}
			else {
				$values["name"]		= $_POST[$formGroupName]["name"];
				$values["email"]	= $_POST[$formGroupName]["email"];
			}
			$controls["name"]["value"]			= $values["name"];
			$controls["email"]["value"]			= $values["email"];
			$controls["text"]["value"]			= $_POST[$formGroupName]["text"];

			// ostatni hodnoty formu
			$form["target"]			= LBOX_REQUEST_URL_PATH;
			$form["name"]			= $formGroupName;
			$form["controls"]		= $controls;
			$form["error"] 			= false;
			return $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>