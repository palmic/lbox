<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class OutputFilterParty extends LBoxOutputFilter
{
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "url":
					$pageDetailID	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_party")->getContent();
					$pageDetail		= LBoxConfigManagerStructure::getInstance()->getPageById($pageDetailID);
					return $pageDetail->url .":". $this->instance->getParamDirect("url");
					break;
				case "datetimeRSS":
					return gmdate("D, d M Y H:i:s", strtotime($this->instance->datetime)). " GMT";
					break;
				default:
					return $value;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>