<?php
/**
 * trida obsahujici funkcnost seznamu vcetne strankovani - pro pouziti v komponentach/strankach zobrazujjicich strankovaci seznamy
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
abstract class ComponentList extends LBoxComponent
{
	/**
	 * @var AbstractRecords
	 */
	protected $cacheRecords;
	
	/**
	 * @var array
	 */
	protected $cachePaging;
	
	/**
	 * Nazev Records tridy
	 * @var string
	 */
	protected $recordsClassName = "";

	/**
	 * Trida OutputFilteru pro records
	 * @var string
	 */
	protected $recordsOutpuFilterClassName	= "";
	
	/**
	 * Nazev promenne configu "paging by"
	 * @var string
	 */
	protected $recordsPagingByConfigVarname = "";

	/**
	 * Nazev Records tridy
	 * @var string
	 */
	protected $recordsOrderBy = false;

	/**
	 * Nazev Records tridy
	 * @var string
	 */
	protected $recordsWhereAdd = false;
	
	protected function executePrepend(PHPTAL $TAL) {
		// DbControl::$debug = true;
		try {
			$TAL->records 	= $this->getRecords();
			$TAL->paging 	= count($this->getPagingRecords()) > 0 ? $this->getPagingRecords() : NULL;
		}
		catch (Exception $e) {
			// paging out of limit - reload prvni stranky
			if ($e->getCode() == LBoxExceptionPage::CODE_PAGING_OUT_OF_LIMIT) {
				$this->reloadPagingFirstPage();
			}
			throw $e;
		}
	}

	/**
	 * vraci pole stranek s Records, ktere listuje
	 * @return array
	 */
	protected function getPagingRecords() {
		try {
			if (strlen($this->recordsClassName) < 1) {
				throw new LBoxExceptionPage("\$recordsClassName: ". LBoxExceptionPage::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_INSTANCE_VAR);
			}
			// ziskani vstupnich parametru
			if (count($this->cachePaging) < 1) {
				$recordsClassName	= $this->recordsClassName;
				$records 			= new $recordsClassName(false, false, false, $this->recordsWhereAdd);
				$itemsCount 		= $records->count();
				$pageLimit			= $this->getPagingBy();
				$current			= $this->getPagingCurrent();
				// cache values
				$this->cachePaging["itemsCount"] 	= $itemsCount;
				$this->cachePaging["pageLimit"] 	= $pageLimit;
				$this->cachePaging["current"] 		= $current;
			}
			else {
				$itemsCount 	= $this->cachePaging["itemsCount"];
				$pageLimit		= $this->cachePaging["pageLimit"];
				$current		= $this->cachePaging["current"];
			}
			if ($itemsCount < 1) {
				return array();
			}
			return $this->getPaging($itemsCount, $pageLimit, $current);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci kolekci records, ktere vypisuje a strankuje
	 * @return AbstractRecords
	 * @throws LBoxException
	 */
	protected function getRecords() {
		try {
			$recordsClassName	= $this->recordsClassName;
			if ($this->cacheRecords instanceof $recordsClassName) {
				return $this->cacheRecords;
			}
			$pagingStart 		= ($this->getPagingCurrent()-1) * $this->getPagingBy();
			$limit				= $this->getPagingBy() > 0 ? array($pagingStart, $this->getPagingBy()) : false;
			$records 			= new $recordsClassName(false, $this->recordsOrderBy, $limit, $this->recordsWhereAdd);
			if (strlen($this->recordsOutpuFilterClassName) > 0) {
				$records->setOutputFilterItemsClass($this->recordsOutpuFilterClassName);
			}
			if ($records->count() < 1 && $this->getPagingCurrent() > 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_PAGING_OUT_OF_LIMIT, LBoxExceptionPage::CODE_PAGING_OUT_OF_LIMIT);
			}
			return $this->cacheRecords = $records;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci aktualni hodnotu limitu parties na stranku pro admin
	 * @return int
	 */
	protected function getPagingBy() {
		try {
			if (strlen($this->recordsPagingByConfigVarname) < 1) {
				throw new LBoxExceptionPage("\$recordsPagingByConfigVarname: ". LBoxExceptionPage::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_INSTANCE_VAR);
			}
			$value 	= LBoxConfigManagerProperties::getInstance()
			->getPropertyByName($this->recordsPagingByConfigVarname)
			->getContent();
			return is_numeric($value) ? (int)$value : parent::getPagingBy();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>