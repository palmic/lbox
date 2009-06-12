<?
/**
 * login formular
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-09-23
*/
class LoginForm extends LBoxComponent
{
	/**
	 * @var LBoxForm
	 */
	protected $form;
	
	/**
	 * used processors
	 * @var array
	 */
	protected $processors = array();

	protected $validators = array();
	
	protected function executePrepend(PHPTAL $TAL) {
		// DbControl::$debug = true;
		try {
			if (count($this->processors) < 1) {
				$this->processors[]	= new ProcessorLogin;
			}
			if (count($this->validators) < 1) {
				$this->validators[]	= new LBoxFormValidatorLogin();
			}
			$controlNick		= new LBoxFormControlFill("nick", "nick", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_nick"));
			$controlNick		->setTemplateFileName("lbox_form_control_nick.html");
			$controlNick		->setRequired();
			$controlPassword	= new LBoxFormControlPassword("password", "heslo", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_password"));
			$controlPassword	->setRequired();
				$controlsLogin			= new LBoxFormControlMultiple("form", "controls");
				$controlsLogin->setTemplateFileName("lbox_form_control_multi_login.html");
				$controlsLogin->addControl		($controlNick);
				$controlsLogin->addControl		($controlPassword);
				foreach ($this->validators as $validator) {
					$controlsLogin->addValidator($validator);
				}
				$controlsLogin->addValidator(new LBoxFormValidatorLogin());
			$form					= new LBoxForm("login", "post", "Přihlášení uživatele", "přihlásit");
			$form->setTemplateFileName("lbox_form_login.html");
			$form->addControl($controlsLogin);
			$form->setAntiSpam(true);
			foreach ($this->processors as $processor) {
				$form->addProcessor($processor);
			}
			$this->form	= $form;

			$xtReloadLoggedParts	= strlen($this->page->xt_reload_logged_xt) > 0 ? explode(":", $this->page->xt_reload_logged_xt) : array();
			$loginGroup				= count($xtReloadLoggedParts) > 0 ? $xtReloadLoggedParts[0] : 1;
			$TAL->form				= $form;
			$TAL->isLogged			= LBoxXTProject::isLogged($loginGroup);
			if (LBoxXT::isLogged($loginGroup)) {
				$TAL->userXTRecord		= LBoxXTProject::getUserXTRecord($loginGroup);
			}
			$TAL->logoutUrl			= $this->getURLLogout();
			$this->logout($loginGroup);
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
	 * odloguje zalogovaneho uzivatele
	 * @param int $loginGroup
	 * @throws Exception
	 */
	protected function logout($loginGroup = 1) {
		try {
			$signedOff	= false;
			foreach ($this->getUrlParamsArray() as $param) {
				if ($this->isUrlParamPaging($param)) continue;
				if ($param == "logout") {
					if (LBoxXT::isLogged($loginGroup)) {
						LBoxXT::getInstance()->logout($loginGroup);
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
}
?>