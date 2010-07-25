<?
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2009-10-11
*/
abstract class PageAdminRecord extends PageDefault
{
	/**
	 * typ recordu
	 * @var string
	 */
	protected $classNameRecord				= "";

	/**
	 * nazev index atributu
	 * @var string
	 */
	protected $colNameURLParam 				= "";

	/**
	 * nazev property s reg exp patternem pro rozpoznani URL param recordu
	 * @var string
	 */
	protected $propertyNamePatternURLParam 	= "";
	
	/**
	 * Record OF type
	 * @var string
	 */
	protected $classNameRecordOutputFilter	= "";
	
	/**
	 * cache record
	 * @var AbstractRecordLBox
	 */
	protected $record;
	
	/**
	 * whereAdd, ktery bude pouzit pri ziskavani zaznamu pres records kolekci
	 * @var QueryBuilderWhere
	 */
	protected $where = NULL;

	protected function executeStart() {
		try {
			if (strlen($this->getRecordURLParam()) > 0 && !$this->getRecord()) {
				LBoxFront::reload(LBoxUtil::getURLWithoutParams(array($this->getRecordURLParam())));
			}
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
	 * getter na admin form
	 * @return LBoxForm
	 */
	abstract public function getForm();

	/**
	 * getter na record podle URL param
	 * @return AbstractRecordLBox
	 */
	public function getRecord() {
		try {
			if ($this->record instanceof AbstractRecordLBox) {
				return $this->record;
			}
			if (strlen($this->getRecordURLParam()) < 1) {
				return;
			}
			if (strlen($classNameRecord = $this->classNameRecord) < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_INSTANCE_VAR);
			}
			$colNameURLParam	= strlen($this->colNameURLParam) > 0 ? $this->colNameURLParam : eval("return $classNameRecord::\$idColName;");
			$classNameRecords	= eval("return $classNameRecord::\$itemsType;");
			$records = new $classNameRecords(array($colNameURLParam => $this->getRecordURLParam()), $order = false, $limit = false, $this->where);
			if (strlen($this->classNameRecordOutputFilter) > 0) {
				$records->setOutputFilterItemsClass($this->classNameRecordOutputFilter);
			}
			return $this->record = $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na URL param
	 * @return string
	 */
	protected function getRecordURLParam() {
		try {
			if (strlen($this->propertyNamePatternURLParam) > 0) {
				return LBoxUtil::getURLParamByPatternProperty($this->propertyNamePatternURLParam);
			}
			else {
				foreach (LBoxFront::getUrlParamsArray() as $param) {
					return $param;
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function getURLParamByPatterProperty($propertyName = "") {
		try {
			return LBoxUtil::getURLParamByPatternProperty($propertyName);
 		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>