<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-28
*/
class OutputFilterModelSchool extends OutputFilterSchool
{
	/**
	 * cache variables
	 */
	protected $isLeader;
	
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "class":
					return $this->getClass();
					break;
				default:
					return parent::prepare($name, $value);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function getClass() {
		try {
			$out	= "";
			if ($this->isLeader()) {
				$out	.= strlen($out) > 0 ? " " : "";
				$out	.= "leader";
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function isLeader() {
		try {
			if (is_bool($this->isLeader)) {
				return $this->isLeader;
			}
			$recordsTopRatingsBySchools	= new TopRatedPhotosBySchoolsRecords(array(	"ref_school" 	=> $this->instance->ref_school,
																					"id" 			=> $this->instance->ref_photo));
			return $this->isLeader		= $recordsTopRatingsBySchools->count() > 0;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>