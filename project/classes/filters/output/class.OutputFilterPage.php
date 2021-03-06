<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class OutputFilterPage extends LBoxOutputFilter
{
	/**
	 * @var string
	 */
	protected $configParamNameWebTitle	= "web_title";

	/**
	 * @var string
	 */
	protected $configParamNamePageTitle	= "page_title_pattern";

	/**
	 * @var string
	 */
	protected $configParamNameHomepageTitle	= "page_home_title_pattern";

	/**
	 * K prepsani na nizsich urovnich pokud ho chteji pouzivat v konfiguracnim patternu pro title stranky
	 * @var string
	 */
	protected $articleHeading			= "";

	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "title":
					$value					= strlen($value) > 0 ? $value : $this->prepare("heading", $this->instance->getParamDirect("heading"));
					$webTitle				= LBoxConfigManagerProperties::getInstance()->getPropertyByName($this->configParamNameWebTitle)->getContent();
					$homePageTitlePattern	= LBoxConfigManagerProperties::getInstance()->getPropertyByName($this->configParamNameHomepageTitle)->getContent();
					$pageTitlePattern	= LBoxConfigManagerProperties::getInstance()->getPropertyByName($this->configParamNamePageTitle)->getContent();
					$pageTitle			= $this->instance->isHomePage() ? $homePageTitlePattern : $pageTitlePattern;
					$pageTitle			= str_replace("\$properties_web_title", $webTitle, 				$pageTitle);
					$pageTitle			= str_replace("\$page_title", 			$value, 				$pageTitle);
					$pageTitle			= str_replace("\$article_heading", 		$this->articleHeading, 	$pageTitle);
					$pageTitle			= trim($pageTitle);
					// v pripade ze mame nakonci samotny oddelovac, odrizneme ho ze stringu
					if (substr($pageTitle, -1) == "|") {
						$pageTitle = trim(substr($pageTitle, 0, strlen($pageTitle)-1));
					}
					return $pageTitle;
				break;
			case "titleMenu":
			case "title_menu":
					return strlen($this->instance->getParamDirect("title")) > 0 ?
							$this->instance->getParamDirect("title") :
							(strlen($this->instance->name_menu) > 0 ? $this->instance->name_menu : $this->prepare("heading", $this->instance->getParamDirect("heading")));
				break;
			case "nameMenu":
			case "name_menu":
			case "headingMenu":
					return strlen($value) > 0 ? $value : $this->instance->heading;
				break;
			case "nameBreadcrumb":
			case "headingBreadcrumb":
					return strlen($value) > 0 ? $value : $this->instance->name_menu;
				break;
			case "titleWeb":
					return LBoxConfigManagerProperties::getInstance()->getPropertyByName($this->configParamNameWebTitle)->getContent();
				break;
			case "description":
			case "keywords":
					return (string)$value;
				break;
			case "isCurrent":
					return ($this->instance->url == LBoxFront::getPage()->url);
				break;
			case "isCurrentBranch":
					if ($this->instance->url == LBoxFront::getPage()->url) {
						return true;
					}
					$parent	= LBoxFront::getPage()->config;
					while ($parent	= $parent->getParent()) {
						if ($parent->url == $this->instance->url) {
							return true;
						}
					}
					return false;
				break;
			case "heading":
					return (string)$value;
				break;
			case "name":
					return $this->instance->heading;
				break;
			case "name_menu":
					if (strlen($value) < 1) {
						$value	= $this->instance->name;
					}
					return $value;
				break;
			case "getChildren":
					return $this->instance->getChildNodesIterator();
				break;
			case "rssPage":
					if (strlen($this->instance->rss) < 1) {
						return NULL;
					}
					else {
						return LBoxConfigManagerStructure::getPageById($this->instance->rss);
					}
				break;
			case "in_menu":
					return $this->instance->is_accesible ? $value : false;
				break;
			case "bodyclass":
					return LBoxFront::getDisplayLanguage();
				break;
			case "is_accesible":
					if ($this->instance->superxt == 1) {
						if (!LBoxXTProject::isLoggedSuperAdmin()) {
							return false;
						}
					}
					if ($this->instance->xt == 1) {
						if (!LBoxXTProject::isLoggedAdmin()) {
							return false;
						}
					}
					return true;
				break;
			case "getClass":
					return $this->getClassMenu();
				break;
			case "getClassBreadcrumb":
					return $this->getClassBreadcrumb();
				break;
			default:
					return $value;
		}
	}

	/**
	 * Vraci CSS classu do menu
	 * @return string
	 * @throws LBoxException
	 */
	protected function getClassMenu() {
		try {
			$className	= "";
			if ($this->instance->isFirstInMenu()) {
				if (strlen($className) > 0) $className .= " ";
				$className .= "first";
			}
			if ($this->instance->isLastInMenu()) {
				if (strlen($className) > 0) {
					$className .= " ";
				}
				$className .= "last";
			}
			return $className;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci CSS classu do drobecku
	 * @return string
	 * @throws LBoxException
	 */
	protected function getClassBreadcrumb() {
		try {
			$className	= "";
			if ($this->instance->isHomePage()) {
				if (strlen($className) > 0) $className .= " ";
				$className .= "first";
			}
			if ($this->prepare("isCurrent")) {
				if (strlen($className) > 0) $className .= " ";
				$className .= "last";
			}
			return $className;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>
