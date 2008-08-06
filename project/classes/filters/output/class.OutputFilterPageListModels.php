<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-01
*/
class OutputFilterPageListModels extends OutputFilterPage
{
	/**
	 * cache vars
	 */
	protected $school;
	protected $city;
	protected $region;
	
	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "heading":
					return $this->getHeading($value);
				break;
			case "title":
					return $this->getTitle($value);
				break;
			default:
					return $value;
		}
	}

	/**
	 * vraci title stranky
	 * @param string $default - defaultni hodnota predana instanci stranky
	 * @return string
	 */
	protected function getTitle($default	= "") {
		try {
			return $this->getHeading($default);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci heading stranky
	 * @param string $default - defaultni hodnota predana instanci stranky
	 * @return string
	 */
	protected function getHeading($default	= "") {
		try {
			switch (true) {
				case $this->isFilteredBySchool():
						$heading	= "$default školy ". $this->getFilterSchool()->name;
					break;
				case $this->isFilteredByCity():
						$heading	= "$default z města ". $this->getFilterCity()->name;
					break;
				case $this->isFilteredByRegion():
						$heading	= "$default z ". $this->getFilterRegion()->name ." kraje";
					break;
			}
			return $heading;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci, jestli je filtrováno podle skoly
	 * @return bool
	 */
	protected function isFilteredBySchool() {
		try {
			return (strlen(LBoxFront::getPage()->getURLParamSchool()) > 0);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci, jestli je filtrováno podle mesta
	 * @return bool
	 */
	protected function isFilteredByCity() {
		try {
			return (strlen(LBoxFront::getPage()->getURLParamCity()) > 0);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci, jestli je filtrováno podle regionu
	 * @return bool
	 */
	protected function isFilteredByRegion() {
		try {
			return (strlen(LBoxFront::getPage()->getURLParamRegion()) > 0);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci skolu, pro kterou je filtrovano
	 * @return SchoolsRecord
	 */
	protected function getFilterSchool() {
		try {
			if ($this->school instanceof SchoolsRecord) {
				return $this->school;
			}
			$records	= new SchoolsRecords(array("id" => LBoxFront::getPage()->getURLParamSchool()));
			return $this->school = $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci skolu, pro kterou je filtrovano
	 * @return CitiesRecord
	 */
	protected function getFilterCity() {
		try {
			if ($this->city instanceof CitiesRecord) {
				return $this->city;
			}
			$records	= new CitiesRecords(array("id" => LBoxFront::getPage()->getURLParamCity()));
			return $this->city = $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci skolu, pro kterou je filtrovano
	 * @return RegionsRecord
	 */
	protected function getFilterRegion() {
		try {
			if ($this->region instanceof RegionsRecord) {
				return $this->region;
			}
			$records	= new RegionsRecords(array("id" => LBoxFront::getPage()->getURLParamRegion()));
			return $this->region = $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>