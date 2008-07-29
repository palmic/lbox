<?
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @date 2007-12-08
 */
class LBoxFront extends LBox
{
	/**
	 * @var LBoxStructureItem
	 */
	protected static $pageCfg;

	/**
	 * @var LBoxPage
	 */
	protected static $page;

	public static $HTTP = array (
	100 => "HTTP/1.1 100 Continue",
	101 => "HTTP/1.1 101 Switching Protocols",
	200 => "HTTP/1.1 200 OK",
	201 => "HTTP/1.1 201 Created",
	202 => "HTTP/1.1 202 Accepted",
	203 => "HTTP/1.1 203 Non-Authoritative Information",
	204 => "HTTP/1.1 204 No Content",
	205 => "HTTP/1.1 205 Reset Content",
	206 => "HTTP/1.1 206 Partial Content",
	300 => "HTTP/1.1 300 Multiple Choices",
	301 => "HTTP/1.1 301 Moved Permanently",
	302 => "HTTP/1.1 302 Found",
	303 => "HTTP/1.1 303 See Other",
	304 => "HTTP/1.1 304 Not Modified",
	305 => "HTTP/1.1 305 Use Proxy",
	307 => "HTTP/1.1 307 Temporary Redirect",
	400 => "HTTP/1.1 400 Bad Request",
	401 => "HTTP/1.1 401 Unauthorized",
	402 => "HTTP/1.1 402 Payment Required",
	403 => "HTTP/1.1 403 Forbidden",
	404 => "HTTP/1.1 404 Not Found",
	405 => "HTTP/1.1 405 Method Not Allowed",
	406 => "HTTP/1.1 406 Not Acceptable",
	407 => "HTTP/1.1 407 Proxy Authentication Required",
	408 => "HTTP/1.1 408 Request Time-out",
	409 => "HTTP/1.1 409 Conflict",
	410 => "HTTP/1.1 410 Gone",
	411 => "HTTP/1.1 411 Length Required",
	412 => "HTTP/1.1 412 Precondition Failed",
	413 => "HTTP/1.1 413 Request Entity Too Large",
	414 => "HTTP/1.1 414 Request-URI Too Large",
	415 => "HTTP/1.1 415 Unsupported Media Type",
	416 => "HTTP/1.1 416 Requested range not satisfiable",
	417 => "HTTP/1.1 417 Expectation Failed",
	500 => "HTTP/1.1 500 Internal Server Error",
	501 => "HTTP/1.1 501 Not Implemented",
	502 => "HTTP/1.1 502 Bad Gateway",
	503 => "HTTP/1.1 503 Service Unavailable",
	504 => "HTTP/1.1 504 Gateway Time-out"
	);

	/**
	 * spousti zobrazeni pozadovane stranky
	 * @throws LBoxException
	 */
	public static function run() {
		try {
			$acces = AccesRecord::getInstance();
			$acces->store();
			$pageCfg = self::getPageCfg();

			// xt
			if ($pageCfg->xt == 1) {
				if (!LBoxXT::isLogged()) {
					self::reloadXTLogin();
				}
				if (!LBoxXT::isLoggedAdmin()) {
					self::reloadHomePage();
				}
			}
			// super xt
			if ($pageCfg->superxt == 1) {
				if (!LBoxXT::isLogged()) {
					self::reloadXTLogin();
				}
				if (!LBoxXT::isLoggedSuperAdmin()) {
					self::reloadHomePage();
				}
			}

			echo trim(self::getPage()->getContent());
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter parametru
	 * @return LBoxStructureItem
	 * @throws LBoxException
	 */
	protected static function getPageCfg() {
		try {
			if (!self::$pageCfg instanceof LBoxStructureItem) {
				try {
					$pageCfg = LBoxConfigManagerStructure::getInstance()->getPageByUrl(LBOX_REQUEST_URL_VIRTUAL);
				}
				catch (Exception $e) {
					// page not found
					if ($e->getCode() == LBoxExceptionConfigStructure::CODE_NODE_BYURL_NOT_FOUND) {
						if (strlen($page404ID = LBoxConfigSystem::getInstance()->getParamByPath("pages/page404")) < 1) {
							throw new LBoxExceptionFront(LBoxExceptionFront::MSG_PAGE404_NOT_DEFINED, LBoxExceptionFront::CODE_PAGE404_NOT_DEFINED);
						}
						$page404Cfg = LBoxConfigManagerStructure::getInstance()->getPageById($page404ID);
						if (!$page404Cfg instanceof LBoxConfigItemStructure) {
							throw new LBoxExceptionFront(LBoxExceptionFront::MSG_PAGE404_NOT_FOUND ." was searching for page-id='$page404ID'", LBoxExceptionFront::CODE_PAGE404_NOT_FOUND);
						}
						self::setHttpHeaderStatus(404);
						$pageCfg = $page404Cfg;
					}
				}
				return self::$pageCfg	= $pageCfg;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}


	/**
	 * vraci instanci sve zobrazovane page
	 * @return LBoxPage
	 */
	public static function getPage() {
		try {
			if (self::$page instanceof LBoxPage) {
				return self::$page;
			}
			$className 		= self::getPageCfg()->getClassName();
			$pageInstance 	= new $className(self::getPageCfg());
				
			if ((!$pageInstance instanceof LBoxPage) && (!$pageInstance instanceof PageList)) {
				throw new LBoxExceptionFront(LBoxExceptionFront::MSG_PAGE_BAD_TYPE, LBoxExceptionFront::CODE_PAGE_BAD_TYPE);
			}
			return self::$page = $pageInstance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * zasle header() podle predaneho statusu
	 * @param int $status
	 */
	public static function setHttpHeaderStatus($status = 200) {
		if (!is_numeric($status) || $status < 1) {
			throw new LBoxExceptionFront(LBoxExceptionFront::MSG_PARAM_INT_NOTNULL, LBoxExceptionFront::CODE_BAD_PARAM);
		}
		if (!array_key_exists($status, self::$HTTP)) {
			throw new LBoxExceptionFront(LBoxExceptionFront::MSG_HTTP_STATUS_NOT_FOUND, LBoxExceptionFront::CODE_HTTP_STATUS_NOT_FOUND);
		}
		header(self::$HTTP[$status]);
	}

	/**
	 * reloaduje na predane, nebo aktualni (bez url params) URL
	 * @param string $url
	 */
	public static function reload($url = "") {
		if (strlen($url) < 1) {
			$url = LBOX_REQUEST_URL_PATH;
		}
		header("Location: $url");
		die();
	}

	/**
	 * reloaduje na homepage
	 * @throws Exception
	 */
	public static function reloadHomePage() {
		try {
			self::reload(LBoxConfigManagerStructure::getInstance()->getHomePage()->url);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * reloaduje na homepage
	 * @throws Exception
	 */
	public static function reloadXTLogin() {
		try {
			if (strlen($pageAdminXTID	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_xt_login")->getContent()) < 1) {
				self::reloadHomePage();
			}
			// pokusime se ziskat stranku ze struktury
			try {
				$pageAdminXT	= LBoxConfigManagerStructure::getInstance()->getPageById($pageAdminXTID);
			}
			catch (LBoxExceptionConfigComponent $e) {
				throw $e;
			}
			self::reload($pageAdminXT->url);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Zjistuje, jestli dany string muze byt URL na soubor s priponou
	 * @return bool
	 * @throws Exception
	 */
	public static function isURLToFile($url = "") {
		try {
			$extMaxLength	= 4;
			return (bool)(strstr(substr($url, -($extMaxLength+1)), "."));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * reloaduje na xt forbidden info page
	 * @throws Exception
	 */
	protected static function reloadXT() {
		try {
			$pageCfg = self::getPageCfg();
			if (strlen($pageCfg->xt_reload) > 0) {
				self::reload(LBoxConfigManagerStructure::getPageById($pageCfg->xt_reload)->url);
			}
			self::reloadHomePage();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * reloaduje na xt default page
	 * @throws Exception
	 */
	protected static function reloadXTLogged() {
		try {
			$pageCfg = self::getPageCfg();
			if (strlen($pageCfg->xt_reload_logged) > 0) {
				$pageReload	= LBoxConfigManagerStructure::getPageById($pageCfg->xt_reload_logged);
				if ($pageCfg->url != $pageReload->url) {
					self::reload($pageReload->url);
				}
			}
			//self::reloadHomePage();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci string z URL za : pokud nejaky je
	 * @return string
	 */
	public static function getUrlParamsString() {
		return trim(LBOX_REQUEST_URL_PARAMS);
	}

	/**
	 * Vraci v poli vsechny URL params, za separator povazuje /
	 * @return string
	 */
	public static function getUrlParamsArray() {
		if (strlen($paramsString = self::getUrlParamsString()) < 1) {
			return array();
		}
		else {
			return explode("/", $paramsString);
		}
	}

	/**
	 * vraci true, jestli predany param vyhovuje vzoru pro strankovani
	 * @param page $param
	 * @return bool
	 */
	public static function isUrlParamPaging($param = "") {
		try {
			$pagingUrlParamPattern = LBoxConfigSystem::getInstance()->getParamByPath("output/paging/paging_url_param_pattern");
			if (strlen($param) < 1) return false;
			return (bool)ereg($pagingUrlParamPattern, $param);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci hodnotu pagingBy (po kolika jednotkach strankujeme) - bude default, nebo prepsat
	 * Jeji defaultni hodnota nastavena v config/system.xml
	 * @return int
	 */
	public static function getPagingBy() {
		try {
			$pagingByDefault = LBoxConfigSystem::getInstance()->getParamByPath("output/paging/paging_by_default");
			if (!is_numeric($pagingByDefault)) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_CFG_PAGING_BY_DEFAULT_NOT_SET, LBoxExceptionPage::CODE_CFG_PAGING_BY_DEFAULT_NOT_SET);
			}
			return (int)$pagingByDefault;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns URL param location
	 * @return string
	 */
	public static function getLocationUrlParam() {
		try {
			foreach (self::getUrlParamsArray() as $param) {
				if (self::isUrlParamPaging($param)) continue;
				return $param;
			}
			return "";
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns get protocol data
	 * @return array
	 */
	public static function getDataGet() {
		return $_GET;
	}

	/**
	 * returns post protocol data
	 * @return array
	 */
	public static function getDataPost() {
		return $_POST;
	}

	/**
	 * returns FILE post data
	 * @return array
	 */
	public static function getDataFile() {
		return $_FILES;
	}

	/**
	 * returns currently displaying language key
	 * @return string
	 */
	public static function getDisplayLanguage() {
		return "cs";
	}

	/**
	 * disabled
	 */
	protected function __construct() {}
}
?>