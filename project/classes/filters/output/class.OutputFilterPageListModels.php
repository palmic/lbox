<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-01
*/
class OutputFilterPageListModels extends OutputFilterPageMaybelline
{
	/**
	 * cache vars
	 */
	protected $school;
	protected $city;
	protected $region;
	
	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "title":
					return parent::prepare($name, $this->getTitle($value));
				break;
			case "heading":
					//return "$value <span class=\"concrete\">&raquo; ". $this->limitString($this->prepare("heading_concrete"), 25) ."</span>";
					return $value;
				break;
			case "headingSub":
					return $this->getHeadingSub();
				break;
			case "heading_concrete":
					return $this->getHeadingConcrete();
				break;
			case "headingBreadcrumb":
					return ($this->instance->class == "ListModels") ? $this->getHeadingConcrete() : parent::prepare($name, $value);
				break;
			default:
					return parent::prepare($name, $value);
		}
	}

	/**
	 * vraci title stranky
	 * @param string $default - defaultni hodnota predana instanci stranky
	 * @return string
	 */
	protected function getTitle($default	= "") {
		try {
			switch (true) {
				case $this->isFilteredBySchool():
						$heading	= "$default ". $this->getFilterSchool()->name;
					break;
				case $this->isFilteredByCity():
						$heading	= "$default ". $this->getFilterCity()->name;
					break;
				case $this->isFilteredByRegion():
						$heading	= "$default ". $this->getFilterRegion()->name ." kraj";
					break;
				default:
					$heading	= $default;
			}
			return $heading;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci string orezany na dany pocet znaku
	 * @param string $string
	 * @param int $limit
	 * @return string
	 */
	protected function limitString($string = "", $limit = 0) {
		try {
			if (strlen($string) < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_PARAM_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_PARAM);
			}
			if (strlen($limit) < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_PARAM_INT_NOTNULL, LBoxExceptionPage::CODE_BAD_PARAM);
			}
			if (strlen($string) <= $limit) {
				return $string;
			}
			else {
				if (function_exists("mb_substr")) {
					return mb_substr($string, 0, $limit-3, "UTF-8") ."...";
				}
				else {
					return substr($string, 0, $limit-3) ."...";
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci cast headingu stranky informujici o konkretizaci dat
	 * (napriklad nazev skoly, jejiz data jsou zobrazena, nebo mesto etc., zalezi jak jsou data filtrovana)
	 * @return string
	 */
	protected function getHeadingConcrete() {
		try {
			switch (true) {
				case $this->isFilteredBySchool():
						$out	= $this->getFilterSchool()->name;
					break;
				case $this->isFilteredByCity():
						$out	= $this->getFilterCity()->name;
					break;
				case $this->isFilteredByRegion():
						$out	= $this->getFilterRegion()->name ." kraj";
					break;
				default:
					$out	= "";
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci podnadpisek se skolou a mestem
	 * @return string
	 */
	protected function getHeadingSub() {
		try {
			$out	= "";
			if ($this->isFilteredByCity()) {
				$out	.= strlen($out) > 0 ? " &raquo; " : "";
				$out	.= $this->getFilterCity()->name;
			}
			if ($this->isFilteredBySchool()) {
				$out	.= strlen($out) > 0 ? " &raquo; " : "";
				$out	.= $this->getFilterSchool()->name;
			}
			return $out;
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