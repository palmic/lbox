<?
/**
 * login formular
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-03-19
*/
class LoginFormAdmin extends LBoxComponent
{
	/**
	 * @var LBoxForm
	 */
	protected $form;
	
	protected function executePrepend(PHPTAL $TAL) {
		// DbControl::$debug = true;
		try {
			$controlNick		= new LBoxFormControlFill("nick", "nick", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_nick"));
			$controlNick		->setTemplateFileName("lbox_form_control_nick.html");
			$controlNick		->setRequired();
			$controlPassword		= new LBoxFormControlPassword("password", "heslo", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_password"));
			$controlPassword->setRequired();
				$controlsLogin			= new LBoxFormControlMultiple("form", "controls");
				$controlsLogin->setTemplateFileName("lbox_form_control_multi_login.html");
				$controlsLogin->addControl		($controlNick);
				$controlsLogin->addControl		($controlPassword);
				$controlsLogin->addValidator(new LBoxFormValidatorLogin);
			$form					= new LBoxForm("login", "post", "Přihlášení", "");
			$form->setTemplateFileName("lbox_form_login.html");
			$form->addControl($controlsLogin);
			$form->setAntiSpam(true);
			$form->addProcessor(new ProcessorLoginAdmin);
			$this->form	= $form;

			$TAL->form				= $form;
			$TAL->xt				= LBoxXT::getInstance();
			$TAL->logoutUrl			= $this->getURLLogout();
			//$TAL->pageRegistration	= LBoxConfigManagerStructure::getInstance()->getPageById(LBoxConfigManagerProperties::getPropertyContentByName("ref_page_xt_registration"));
			$this->logout();
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
}
?>