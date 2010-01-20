<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-09-07
*/
class OutputFilterInquiry extends OutputFilterRecordEditableByAdmin
{
	protected $propertyNameRefPageEdit		= "ref_page_xt_edit_inquiry";

	/**
	 * cache variable
	 * @var LBoxForm
	 */
	protected $form;

	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "name":
				return $this->instance->question;
			case "didUserVotedFor":
				return $this->didUserVotedFor();
			default:
				return parent::prepare($name, $value);
		}
	}

	/**
	 * vraci jestli uz uzivatel hlasoval pro tuto anketu
	 * @return bool
	 */
	public function didUserVotedFor() {
		try {
			if ($this->didUserVotedForByCookie()) {
				return true;
			}
			if ($this->didUserVotedForByIP()) {
				return true;
			}
			
			return false;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci jestli uz uzivatel hlasoval pro tuto anketu
	 * - podle IP
	 * @return bool
	 */
	public function didUserVotedForByIP() {
		try {
			$records	= new InquiriesOptionsResponsesRecords(array(	"ref_inquiry" 	=> $this->instance->id,
																		"ip" 			=> AccesRecord::getInstance()->ip));
			return $records->count() > 0;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci jestli uz uzivatel hlasoval pro tuto anketu
	 * - podle cookie
	 * @return bool
	 */
	public function didUserVotedForByCookie() {
		try {
			$cookieName		= "lbox-inquiry-voted-in-". $this->instance->id;
			// session
			if (array_key_exists($cookieName, $_SESSION)) {
				if (strlen((string)$_SESSION[$cookieName]) > 0) {
					@LBoxFront::setCookie($cookieName, (string)time(), time() + LBoxFormValidatorSubmitedYetCookie::$cookiePersistenceDays * 24*60*60, "/");
					return true;
				}
			}
			// cookie
			if (array_key_exists($cookieName, $_COOKIE)) {
				if (strlen((string)$_COOKIE[$cookieName]) > 0) {
					$_SESSION[$cookieName]	= (string)time();
					return true;
				}
			}
			return false;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>