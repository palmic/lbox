<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-09-03
*/
abstract class PageRecordsList extends PageDefault
{
	/**
	 * Iterated Record items type
	 * @var string
	 */
	protected $classNameRecord	= "";
	
	/**
	 * Iterated Record OF type
	 * @var string
	 */
	protected $classNameRecordOutputFilter	= "";
	
	/**
	 * filter AbstractRecord parameter
	 * array
	 */
	protected $filter						= false;

	/**
	 * whereAdd AbstractRecord parameter
	 * QueryBuilderWhere
	 */
	protected $where						= NULL;
	
	/**
	 * orderBy AbstractRecord parameter
	 * @var array
	 */
	protected $orderBy	= false;
	
	/**
	 * limit AbstractRecord parameter
	 * @var array
	 */
	protected $limit	= false;
	
	/**
	 * if > 0, records bude paging
	 * @var int
	 */
	protected $pageItems = 0;
	
	/**
	 * 
	 * @var string
	 */
	protected $propertyNamePagingPageRange = "paging_list_records_paging_range";
	
	/**
	 * 
	 * @var string
	 */
	protected $propertyNamePagingBy = "paging_list_records_page_length";
	
	/**
	 * 
	 * @var string
	 */
	protected $propertyNameRefPageEdit		= "";
	
	/**
	 * cache records
	 * @var AbstractRecords
	 */
	protected $records;

	/**
	 * cache records
	 * @var LBoxPagingIterator
	 */
	protected $recordsPaging;
	
	/**
	 * cache var
	 * @var LBoxPage
	 */
	protected $pageEdit;

	protected function executeStart() {
		try {
			parent::executeStart();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na records
	 * @return AbstractRecords
	 */
	public function getRecords() {
		try {
			if ($this->records instanceof AbstractRecords) {
				return $this->records;
			}
			if (strlen($this->classNameRecord) < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_INSTANCE_VAR);
			}
			$classNameRecords		= eval("return ". $this->classNameRecord ."::\$itemsType;");
			if (is_numeric($this->pageItems) && $this->pageItems > 0) {
				$this->records = new LBoxPagingIteratorRecords($classNameRecords, $this->pageItems, $this->classNameRecordOutputFilter, $this->filter, $this->orderBy, $this->limit, $this->where);
			}
			else {
				$this->records			= new $classNameRecords($this->filter, $this->orderBy, $this->limit, $this->where);
				if (strlen($this->classNameRecordOutputFilter) > 0) {
					$this->records->setOutputFilterItemsClass($this->classNameRecordOutputFilter);
				}
			}
/*DbControl::$debug	= true;
$this->records->count();
DbControl::$debug	= false;*/
			return $this->records;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na paging records
	 * @return LBoxPagingIterator
	 */
	public function getRecordsPaging() {
		try {
			if ($this->recordsPaging instanceof LBoxPagingIterator) {
				return $this->recordsPaging;
			}
			if (strlen($this->classNameRecord) < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_INSTANCE_VAR);
			}
			$classNameRecords			= eval("return ". $this->classNameRecord ."::\$itemsType;");
			$this->recordsPaging	= new LBoxPagingIteratorRecords($classNameRecords, $this->getPagingBy(), $this->classNameRecordOutputFilter, $this->filter, $this->orderBy, $this->limit, $this->where);
			return $this->recordsPaging;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci paging range z configu
	 * @return int
	 */
	protected function getPagingRange() {
		try {
			$out = LBoxConfigManagerProperties::getInstance()
							->getPropertyByName($this->propertyNamePagingPageRange)
							->getContent();
			if (strlen($out) < 1) {
				$out = (int)LBoxConfigSystem::getInstance()->getParamByPath("output/paging/paging_by_default");
			}
			return (int)$out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function getPagingBy() {
		try {
			return LBoxConfigManagerProperties::getPropertyContentByName($this->propertyNamePagingBy);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na stranku s editaci
	 * @return LBoxPage
	 */
	public function getPageEdit() {
		try {
			if ($this->pageEdit instanceof LBoxPage) {
				return $this->pageEdit;
			}
			if (strlen($this->propertyNameRefPageEdit) < 1) {
				return NULL;
			}
			$this->pageEdit	= LBoxConfigManagerStructure::getInstance()->getPageById(LBoxConfigManagerProperties::getPropertyContentByName($this->propertyNameRefPageEdit));
			$this->pageEdit	->setOutputFilter(new OutputFilterPage($this->pageEdit));
			return $this->pageEdit;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na prvni URL param
	 * @return string
	 */
	protected function getURLParamFirst() {
		try {
			foreach (LBoxFront::getUrlParamsArray() as $param) {
				return $param;
			}
 		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na prvni URL param
	 * @return string
	 */
	protected function getURLParamByPatterProperty($propertyName = "") {
		try {
			if (strlen($propertyName) < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_PARAM_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_PARAM);
			}
			$pattern	= LBoxConfigManagerProperties::getPropertyContentByName($propertyName);
			$pattern	= str_ireplace("<url_param>", "([-\w]+)", $pattern);
			foreach (LBoxFront::getUrlParamsArray() as $param) {
				if (preg_match("/$pattern/", $param, $matches)) {
					return $matches[1];
				}
			}
 		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>