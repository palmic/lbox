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