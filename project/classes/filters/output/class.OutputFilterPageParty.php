<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-23
*/
class OutputFilterPageParty extends OutputFilterPage
{
	/**
	 * party database record
	 * @var PartiesRecord
	 */
	protected $party;

	public function setPartyRecord(PartiesRecord $record) {
		$this->party = $record;
	}

	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "title":
				if (!$this->party instanceof PartiesRecord) {
					$class = get_class($this);
					throw new LBoxExceptionPage("You have to set party database record via setPartyRecord() setter before get values via '$class' instance!");
				}
				$webTitle			= LBoxConfigManagerProperties::getInstance()->getPropertyByName("web_title")->getContent();
				$pageTitlePattern	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("page_title_pattern_party")->getContent();
				$pageTitle			= $pageTitlePattern;
				$pageTitle			= str_replace("\$properties_web_title", $webTitle, 					$pageTitle);
				$pageTitle			= str_replace("\$page_title", 			$value, 					$pageTitle);
				$pageTitle			= str_replace("\$party_name",			$this->party->name, 		$pageTitle);
				$pageTitle			= trim($pageTitle);
				// v pripade ze mame nakonci samotny oddelovac, odrizneme ho ze stringu
				if (substr($pageTitle, -1) == "|") {
					$pageTitle = trim(substr($pageTitle, 0, strlen($pageTitle)-1));
				}
				return $pageTitle;
				break;
			default:
				return parent::prepare($name, $value);
		}
	}
}
?>