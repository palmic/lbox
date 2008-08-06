<?php
/**
 * @author Michal Palma <palmic@email.cz>
 * @package LBox ubytovny-v-praze.cz
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @since 2008-07-28
 */
class MailRegistrationConfirm
{
	/**
	 * nazev sablony HTML verze emailu
	 * @var string
	 */
	protected $fileNameTemplateHTML	= "registration_confirm.html";

	/**
	 * TAL instance
	 * @var PHPTAL
	 */
	protected $TAL;

	/**
	 * MIMEMail class instance
	 * @var HTMLMIMEMail5
	 */
	protected $MIMEMail;

	/**
	 * instance XT uzivatele, kteremu je mail urcen
	 * @var XTUserRecord
	 */
	protected $userXT;

	/**
	 * @param int $userXTID
	 */
	public function __construct(XTUsersRecord $userXT) {
		try {
			$this->userXT	= $userXT;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * spousti celou akci
	 * @throws Exception
	 */
	public function init() {
		try {
			$this->MIMEMail	= new HTMLMIMEMail5();
			$this->MIMEMail->setFrom($this->getFrom());
			$this->MIMEMail->setSubject($this->getSubject());
			$this->MIMEMail->setHeadCharset("UTF-8");
			$this->MIMEMail->setHTMLCharset("UTF-8");
			$this->MIMEMail->setHTML($this->getHTML());
			if (!$this->MIMEMail->send((array)$this->getUserXT()->email)) {
				throw new LBoxExceptionPage("Cannot send to address '". $this->getUserXT()->email ."'");
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci obsah HTML verze mailu
	 * @return string
	 * @throws Exception
	 */
	protected function getHTML() {
		try {
			$pathTemplate	= $this->getTemplatesPath() ."/". $this->fileNameTemplateHTML;
			if (!file_exists($pathTemplate)) {
				throw new LBoxExceptionPage("Cannot find HTML mail template file in '$pathTemplate'!");
			}
			$TAL	= $this->getTAL($pathTemplate);
			$out	= $TAL->execute();

			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na TAL
	 * @return PHPTAL
	 */
	protected function getTAL($pathTemplate = "") {
		try {
			if (strlen($pathTemplate) < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_PARAM_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_PARAM);
			}
			if (!$this->TAL instanceof PHPTAL) {
				$this->TAL = new PHPTAL($pathTemplate);
			}
			$this->TAL->SELF 		= $this;
			return $this->TAL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci instanci usera, kteremu bude mail zasilan
	 *  - public kvuli moznemu volani ze sablony
	 * @return XTUsersRecord
	 */
	public function getUserXT() {
		return $this->userXT;
	}

	/**
	 * vraci cestu k adresari sablon emailu
	 * @return string
	 */
	protected function getTemplatesPath() {
		try {
			return LBoxConfigSystem::getInstance()->getParamByPath("emails/templates/path");
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci odesilatele emailu
	 * @return string
	 */
	protected function getFrom() {
		try {
			return LBoxConfigManagerProperties::getInstance()->getPropertyByName("registration_confirm_mail_from")->getContent();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci subject emailu
	 * @return string
	 */
	protected function getSubject() {
		try {
			$out	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("registration_confirm_mail_subject")->getContent();
			$out	= str_ireplace("<host>", $this->getHost(), $out);
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na host pro sablonu
	 * @return string
	 */
	public function getHost() {
		try {
			return LBOX_REQUEST_URL_HOST;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na confirm URL
	 * @return string
	 */
	public function getURLConfirm() {
		try {
			$pageID	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_registration_confirm")->getContent();
			return LBoxConfigManagerStructure::getInstance()->getPageById($pageID)->url .":". $this->getUserXT()->hash;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>