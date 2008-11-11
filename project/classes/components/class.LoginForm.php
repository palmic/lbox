<?
/**
 * login formular
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class LoginForm extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		// DbControl::$debug = true;
		try {
			$TAL->form				= $this->getFormArray();
			$TAL->xt				= LBoxXT::getInstance();
			$TAL->logoutUrl			= $this->getURLLogout();
			$TAL->registrationUrl	= $this->getURLRegistration();
			$this->logout();
			try {
				$this->checkData();
			}
			catch (LBoxExceptionForm $eF) {
				$TAL->form	= $eF->getFormArray();
			}
		}
		catch (Exception $e) {
			if ($e->getCode() == LBoxExceptionXT::CODE_USER_NOT_CONFIRMED) {
				$TAL->userNotConfirmed	= true;
			}
			else {				
				throw $e;
			}
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
			// kontrola required
			foreach ($controls as $name => $control) {
				if ($control["required"]) {
					if (strlen(strip_tags($control["value"])) < 1) {
						$control["error"]["empty"] = true;
						$error = true;
					}
				}
				// kontrola detailni
				if (!$error)
				switch ($name) {
					case "password":
						// login
						$remember	= ($formArray["controls"]["remember"]["checked"] == "checked");
						if (!$this->isLogginSuccesfull($formArray["controls"]["nick"]["value"], $control["value"], $remember)) {
							$control["error"]["loginInvalid"] = true;
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
						case "nick":
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
	 * vraci pole s daty formulare a dalsimi vecmi
	 * @return array
	 */
	protected function getFormArray() {
		try {
			$formGroupName			= $this->getFormGroupName();
			$form 					= array();
			$controls				= array();

			// id
			$controls["nick"]["id"]				= $formGroupName. "-nick";
			$controls["password"]["id"]			= $formGroupName. "-password";
			$controls["remember"]["id"]			= $formGroupName. "-remember";
			
			// groupName
			$controls["nick"]["name"]				= $formGroupName. "[nick]";
			$controls["password"]["name"]			= $formGroupName. "[password]";
			$controls["remember"]["name"]			= $formGroupName. "[remember]";
			
			// required
			$controls["nick"]["required"]			= true;
			$controls["password"]["required"]		= true;
			$controls["remember"]["required"]		= false;
			
			// data - pokud neexistuji postdata, budou tam prazdne stringy
			$controls["nick"]["value"]		= $_POST[$formGroupName]["nick"];
			$controls["password"]["value"]	= $_POST[$formGroupName]["password"];
			$controls["remember"]["value"]	= 1;

			// checked
			$controls["remember"]["checked"]	= ($_POST[$formGroupName]["remember"] == 1) ? "checked" : "";

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

	/**
	 * Pokusi se uzivatele zalogovat a vraci true, jestli se mu to povedlo
	 * @param string $nick
	 * @param string $password
	 * @param bool $remember
	 * @return bool
	 * @throws Exception
	 */
	protected function isLogginSuccesfull($nick = "", $password = "", $remember = false) {
		try {
			if (strlen($nick) < 1) {
				throw new LBoxExceptionComponent("\$nick ". LBoxExceptionComponent::MSG_PARAM_STRING_NOTNULL, LBoxExceptionComponent::CODE_BAD_PARAM);
			}
			if (strlen($password) < 1) {
				throw new LBoxExceptionComponent("\$password ". LBoxExceptionComponent::MSG_PARAM_STRING_NOTNULL, LBoxExceptionComponent::CODE_BAD_PARAM);
			}
			try {
				$xt	= LBoxXT::login($nick, $password, $remember);
				$this->reload();
			}
			catch (LBoxExceptionXT $e) {
				if ($e->getCode() == LBoxExceptionXT::CODE_LOGIN_INVALID) {
					return false;
				}
				throw $e;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * odloguje zalogovaneho uzivatele
	 * @throws Exception
	 */
	protected function logout() {
		try {
			$signedOff	= false;
			foreach ($this->getUrlParamsArray() as $param) {
				if ($this->isUrlParamPaging($param)) continue;
				if ($param == "logout") {
					if (LBoxXT::isLogged()) {
						LBoxXT::getInstance()->logout();
						$signedOff	= true;
					}
				}
				else {
					$paramsNew[] = $param;
				}
			}
			$glue	= count($paramsNew) > 0 ? ":" : "";
			if ($signedOff) {
				$this->reload(LBOX_REQUEST_URL_VIRTUAL .$glue. implode("/", (array)$paramsNew));
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vrati kompletni logout URL
	 * @return string
	 * @throws Exception
	 */
	protected function getURLLogout() {
		try {
			$glue = (count($this->getUrlParamsArray()) > 0) ? "/" : ":";
			return LBOX_REQUEST_URL_PATH . $glue . "logout";
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vrati kompletni URL na stranku s registraci
	 * @return string
	 * @throws Exception
	 */
	protected function getURLRegistration() {
		try {
			$pageId	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_xt_registration")->getContent();
			return LBoxConfigManagerStructure::getPageById($pageId)->url;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>