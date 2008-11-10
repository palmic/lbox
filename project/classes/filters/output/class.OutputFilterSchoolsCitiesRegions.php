<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-09-11
*/
class OutputFilterSchoolsCitiesRegions extends LBoxOutputFilter
{
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "isEmpty":
						return $this->isEmpty();
					break;
				case "class":
						return $this->getClass();
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
	 * vraci bool, jestli je skola prazdna z hlediska vyplnenych soutezicich
	 * @return bool
	 */
	protected function isEmpty() {
		try {
			return $this->instance->models_count < 1;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci css class
	 * @return string
	 */
	protected function getClass() {
		try {
			if ($this->isEmpty()) {
				return "empty";
			}
			else {
				return "";
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>