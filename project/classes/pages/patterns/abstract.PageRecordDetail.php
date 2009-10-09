<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-09-03
*/
abstract class PageRecordDetail extends PageDefault
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
	 * attribute, ktery bude pouzit jako filter pro vytahnuti zaznamu ze systemu
	 * @var string
	 */
	protected $colNameURLParam	= "";
	
	/**
	 * cache records
	 * @var AbstractRecord
	 */
	protected $record;

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
	 * getter na record
	 * @return AbstractRecord
	 */
	public function getRecord() {
		try {
			if ($this->record instanceof AbstractRecord) {
				return $this->record;
			}
			if (strlen($this->classNameRecord) < 1) {
				throw new LBoxExceptionPage("classNameRecord: ". LBoxExceptionPage::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_INSTANCE_VAR);
			}
			if (strlen($this->colNameURLParam) < 1) {
				throw new LBoxExceptionPage("colNameURLParam: ". LBoxExceptionPage::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_INSTANCE_VAR);
			}
			if (strlen($this->getURLParamRecord()) < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_URL_PARAM_EMPTY, LBoxExceptionPage::CODE_URL_PARAM_EMPTY);
			}
			$classNameRecords		= eval("return ". $this->classNameRecord ."::\$itemsType;");
			$records				= new $classNameRecords(array($this->colNameURLParam => $this->getURLParamRecord()));
			if (strlen($this->classNameRecordOutputFilter) > 0) {
				$records->setOutputFilterItemsClass($this->classNameRecordOutputFilter);
			}
			if ($records->count() < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_DISPLAY_ITEM_NOT_FOUND, LBoxExceptionPage::CODE_DISPLAY_ITEM_NOT_FOUND);
			}
			if ($records->count() > 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_DISPLAY_ITEM_FOUND_MORE, LBoxExceptionPage::CODE_DISPLAY_ITEM_FOUND_MORE);
			}
			return $this->record	= $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na URL param zaznamu
	 * @return string
	 */
	protected function getURLParamRecord() {
		try {
			foreach (LBoxFront::getUrlParamsArray() as $param) {
				if (LBoxFront::isUrlParamPaging($param)) continue;
				return $param;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>