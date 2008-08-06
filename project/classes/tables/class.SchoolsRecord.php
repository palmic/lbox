<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-24
*/
class SchoolsRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "SchoolsRecords";
	public static $tableName    	= "schools";
	public static $idColName    	= "id";

	public static $boundedM1 = array("CitiesRecords" => "ref_city");
	public static $bounded1M = array("ModelsRecords" => "ref_school");

	/**
	 * cache variables
	 */
	protected $models;
	protected $city;
	protected $region;
	protected $xTUserVotedCount;
	protected $timeLastCreated;

	public function __construct($id = 0) {
		try {
			$this->setOutputFilter(new OutputFilterSchool($this));
			return parent::__construct($id);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci, jestli uzivatel prekrocil hlasovaci limit
	 * @return bool
	 * @throws LBoxException
	 */
	public function hasUserReachedVotedLimit() {
		try {
			return ($this->getXTUserVotedCount()
					>=LBoxConfigManagerProperties::getPropertyContentByName("max_user_votes_per_school"));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci pocet hlasu momentalne prihlaseneho uzivatele pro fotky soutezicich teto skoly
	 * @return int
	 * @throws LBoxException
	 */
	public function getXTUserVotedCount() {
		try {
			if (is_numeric($this->xTUserVotedCount)) {
				return $this->xTUserVotedCount;
			}
			$records	= new PhotosRatingsXTUsersRecords(array("ref_school" => $this->get("id"), "email" => LBoxXT::getUserXTRecord()->email));
			return $this->xTUserVotedCount	= $records->count();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci timestamp posledniho zapsaneho souteziciho teto skoly
	 * @return int
	 * @throws LBoxException
	 */
	public function getTimeLastModelCreated() {
		try {
			if (is_numeric($this->timeLastCreated)) {
				return $this->timeLastCreated;
			}
			$record	= new SchoolsLastCreatedRecord($this->get("id"));
			return $this->timeLastCreated	= $record->last_created;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci, jestli bylo hlasovani pro tuto skolu jiz uzavreno
	 * @return bool
	 * @throws LBoxException
	 */
	public function isVotingClosed() {
		try {
			return (time() > $this->getLastVotingDayTime());
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci timestamp posledniho hlasovaciho dne
	 * @return int
	 * @throws LBoxException
	 */
	public function getLastVotingDayTime() {
		try {
			$lastVotingDayStamp	= $this->getTimeLastModelCreated()
				+ LBoxConfigManagerProperties::getPropertyContentByName("voting_range_school_days") * 3600 * 24;
			return strtotime(date("Y-m-d", $lastVotingDayStamp));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na city
	 * @return CitiesRecord
	 * @throws Exception
	 */
	public function getCity() {
		try {
			if ($this->city instanceof CitiesRecord) {
				return $this->city;
			}
			return $this->city = $this->getBoundedM1Instance("CitiesRecords", $filter, $order, $limit, $whereAdd)->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na region
	 * @return RegionsRecord
	 * @throws Exception
	 */
	public function getRegion() {
		try {
			if ($this->region instanceof RegionsRecord) {
				return $this->region;
			}
			return $this->region	= $this->getCity()->getRegion();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na models
	 * @return ModelsRecords
	 * @throws Exception
	 */
	public function getModels() {
		try {
			if ($this->models instanceof ModelsRecords) {
				return $this->models;
			}
			return $this->models	= $this->getBounded1MInstance("ModelsRecords", $filter, $order, $limit, $whereAdd);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>