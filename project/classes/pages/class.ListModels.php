<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-31
*/
class ListModels extends PageDefault
{
	/**
	 * cache variable
	 * @var array
	 */
	protected $models;

	/**
	 * groupname pouzite u poli hlasovacich formularu
	 * @var string
	 */
	protected $votingFormsGroupName	= "vote";
	
	protected function executeStart() {
		try {
			$this->config->setOutputFilter(new OutputFilterPageListModels($this->config));
			// checknout strankovani out-of-range
			if ($this->getModels()->count() < ($this->getPagingCurrent()-1) * $this->getListPaging()+1) {
				$this->reloadPagingFirstPage();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
			$this->processVote();
			$TAL->models			= $this->getModels($this->getPagingFilter());
			$TAL->voteGroupName		= $this->votingFormsGroupName;
			$TAL->xtLogged			= LBoxXT::isLogged();
			$TAL->paging			= $this->getPaging2($this->getModels()->count(), $this->getListPaging(), $this->getPagingRange());
			$TAL->votingLimitSchool	= LBoxConfigManagerProperties::getPropertyContentByName("max_user_votes_per_school");
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Zpracuje hlasovani pokud bylo odeslano
	 */
	protected function processVote() {
		try {
			$data	= LBoxFront::getDataPost();
			$data	= $data[$this->votingFormsGroupName];
			if (count($data) < 1) return;

			// zjistime, jestli fotka existuje
			$photos	= new PhotosRecords(array("id" => $data["votefor"]));
			if ($photos->count() < 1) return;

			// zapsat hlas
			$photos->current()->rateByCurrentXTUser(10);
			LBoxFront::reload();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci iterator soutezicich a jejich fotek
	 * @return ModelsPhotosRatingsXTUsersRecords
	 * @throws LBoxException
	 */
	protected function getModels($limit	= false) {
		try {
			$key	= md5(serialize($limit));
			if ($this->models[$key] instanceof ModelsPhotosRatingsXTUsersRecords) {
				return $this->models[$key];
			}
			$filter = false;
			if (strlen($urlParams["region"] = $this->getURLParamRegion()) > 0) {
				$filter["ref_region"]	= $urlParams["region"];
			}
			if (strlen($urlParams["city"] = $this->getURLParamCity()) > 0) {
				$filter["ref_city"]	= $urlParams["city"];
			}
			if (strlen($urlParams["school"] = $this->getURLParamSchool()) > 0) {
				$filter["ref_school"]	= $urlParams["school"];
			}
			$this->models[$key]	= new ModelsPhotosRatingsXTUsersRecords($filter, array("rating_count" => 0), $limit);
			$this->models[$key]->setOutputFilterItemsClass("OutputFilterSchool");
			return $this->models[$key];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci URL param regionu
	 * @return string
	 */
	public function getURLParamRegion() {
		try {
			$priorURLParamOrder		= 1;
			$i 						= 1;
			$paramValue				= "";
			foreach ($this->getUrlParamsArray() as $param) {
				if ($i == $priorURLParamOrder) {
					$paramValue	= $param;
					break;
				}
				$i++;
			}
			return $paramValue;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci URL param mesta
	 * @return string
	 */
	public function getURLParamCity() {
		try {
			$priorURLParamOrder		= 2;
			$i 						= 1;
			$paramValue				= "";
			foreach ($this->getUrlParamsArray() as $param) {
				if ($i == $priorURLParamOrder) {
					$paramValue	= $param;
					break;
				}
				$i++;
			}
			return $paramValue;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci URL param skoly
	 * @return string
	 */
	public function getURLParamSchool() {
		try {
			$priorURLParamOrder		= 3;
			$i 						= 1;
			$paramValue				= "";
			foreach ($this->getUrlParamsArray() as $param) {
				if ($i == $priorURLParamOrder) {
					$paramValue	= $param;
					break;
				}
				$i++;
			}
			return $paramValue;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci pole s filtrem pro ziskavani items z databaze
	 * @return array
	 * @throws LBoxException
	 */
	protected function getPagingFilter() {
		try {
			$start	= ($this->getPagingCurrent()-1) * $this->getListPaging();
			return array($start, $this->getListPaging());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci hodnotu paging (po kolika se ma strankovat)
	 * @return int
	 * @throws LBoxException
	 */
	protected function getListPaging() {
		try {
			$out = LBoxConfigManagerProperties::getInstance()
							->getPropertyByName("paging_list_models")
							->getContent();
			if (strlen($out) < 1) {
				$out = LBoxConfigSystem::getInstance()->getParamByPath("output/paging/paging_by_default");
			}
			return (int)$out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci maximalni rozsah strankovani
	 * @return int
	 * @throws LBoxException
	 */
	protected function getPagingRange() {
		try {
			$out = LBoxConfigManagerProperties::getInstance()
							->getPropertyByName("paging_range")
							->getContent();
			if (strlen($out) < 1) {
				$out = 0;
			}
			return (int)$out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>