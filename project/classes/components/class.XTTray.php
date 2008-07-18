<?
/**
 * breadcrumb navigation class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-03-12
*/
class XTTray extends LBoxComponent
{
	/**
	 * nazev URL param pro logout
	 * @var string
	 */
	protected $urlParamNameLogout	= "logout";
	
	protected function executePrepend(PHPTAL $TAL) {
		try {
			// odlogovat uzivatele, jestli ma byti
			if ($this->isToLogout()) {
				$this->logout();
			}

			$urls["logout"]		= LBOX_REQUEST_URL_VIRTUAL .":". $this->urlParamNameLogout;
			$pagesCFG["admin"]	= LBoxConfigManagerStructure::getInstance()->getPageById(
									LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_xt_admin")->getContent()
									);
			// nastaveni filtru
			foreach ($pagesCFG as $pageCFG) {
				$pageCFG->setOutputFilter(new OutputFilterPage($pageCFG));
			}
			$TAL->isLogged	= LBoxXT::isLogged();
			$TAL->urls		= $urls;
			$TAL->pagesCFG	= $pagesCFG;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * zjisti, jestli uzivatel neklikl na logout
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
	 * odloguje uzivatele
	 * @return bool
	 */
	protected function logout() {
		try {
			LBoxXT::logout();
			$this->reload(LBOX_REQUEST_URL_VIRTUAL);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>