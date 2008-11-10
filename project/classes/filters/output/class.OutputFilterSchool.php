<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-04
*/
class OutputFilterSchool extends LBoxOutputFilter
{
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "date_voting_end":
					return date("j.n.Y", $this->instance->getLastVotingDayTime());
					break;
				case "page_list_models_url":
					return $this->getPageListModelsURL();
					break;
				default:
					return $value;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci URL na stranku vypisujici jeji soutezici
	 * @return string
	 * @throws LBoxException
	 */
	protected function getPageListModelsURL() {
		try {//:regionID/cityID/schoolID
			$pageListSchools	= LBoxConfigManagerStructure::getInstance()->getPageById(
									LBoxConfigManagerProperties::getPropertyContentByName("ref_page_list_models"));
			$region	= $this->instance->getRegion();
			return $pageListSchools->url	.":". $this->instance->getRegion()->id ."/". $this->instance->getCity()->id ."/". $this->instance->id ."";
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>