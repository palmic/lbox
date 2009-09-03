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
	 * cache records
	 * @var AbstractRecords
	 */
	protected $records;

	/**
	 * cache records
	 * @var LBoxPagingIterator
	 */
	protected $recordsPaging;

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
			$this->records			= new $classNameRecords(/*$filter = false*//*, $order = false*//*, $limit = false*//*, QueryBuilderWhere $whereAdd*/);
			if (strlen($this->classNameRecordOutputFilter) > 0) {
				$this->records->setOutputFilterItemsClass($this->classNameRecordOutputFilter);
			}
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
			$this->recordsPaging	= new LBoxPagingIteratorRecords($classNameRecords, $this->getPagingBy(), $this->classNameRecordOutputFilter/*, $filter = false*//*, $order = false*//*, $limit = false*//*, QueryBuilderWhere $whereAdd*/);
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
}
?>