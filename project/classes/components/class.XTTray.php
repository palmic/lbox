<?
/**
 * breadcrumb navigation class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-05-25
*/
class XTTray extends LBoxComponent
{
	/**
	 * nazev URL param pro logout
	 * @var string
	 */
	protected $urlParamNameLogout	= "logout";

	protected function executeStart() {
		try {
			parent::executeStart();
			if ($this->isToLogout()) {
				$this->logout();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executePrepend(PHPTAL $TAL) {
		try {
//DbControl::$debug=true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * zjisti, jestli se ma odlogovat
	 * @return bool
	 */
	protected function isToLogout() {
		try {
			return (is_numeric(array_search($this->urlParamNameLogout, $this->getUrlParamsArray())));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter jestli je uzivatel zalogovan
	 * @return bool
	 */
	public function isLogged() {
		try {
			return LBoxXTProject::isLogged();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter jestli je uzivatel zalogovan jako admin
	 * @return bool
	 */
	public function isLoggedAdmin() {
		try {
			return LBoxXTProject::isLoggedAdmin();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * odloguje uzivatele
	 * @return bool
	 */
	protected function logout() {
		try {
			LBoxXTProject::logout();
			LBoxFront::reload(LBOX_REQUEST_URL_VIRTUAL);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na nazev URL parametru pro logout
	 * @return string
	 */
	public function getURLParamNameLogout() {
		try {
			return $this->urlParamNameLogout;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na root stranku adminu
	 * @return LBoxConfigItemStructure
	 */
	public function getPageRootAdmin() {
		try {
			$node	= LBoxConfigManagerStructure::getInstance()
							->getPageById(LBoxConfigManagerProperties::getPropertyContentByName("ref_page_xt_admin"));
			$node->setOutputFilter(new OutputFilterPage($node));
			return $node;
		}
		catch(Exception $e) {
			throw $e;
		}
	} 
}
?>