<?php

/**
 * adds LBoxOutputFilter compatibility
 * @author Michal Palma <michal.palma@gmail.com>
 * @date 2007-11-03
 */
abstract class AbstractRecordsTreeLBox extends AbstractRecordsTree
{
	/**
	 * items OutputFilter class
	 * @var string
	 */
	protected $outputFilterClass;

	/**
	 * overloaded with front cache logic
	 */
	public function __construct($filter = false, $order = false, $limit = false, QueryBuilderWhere $whereAdd = NULL) {
		try {
			// do inform front cache manager that this record type data hapen to be used at this URL
			LBoxCacheManagerFront::getInstance()->addRecordType($this->getClassVar("itemType"));
			
			return parent::__construct($filter, $order, $limit, $whereAdd);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Set items OutputFilter class
	 * @param string $outputFilterClass
	 */
	public function setOutputFilterItemsClass($outputFilterClass = "") {
		if (strlen($outputFilterClass) < 1) {
			throw new LBoxExceptionOutputFilter("\$outputFilterClass ". LBoxExceptionOutputFilter::MSG_PARAM_STRING_NOTNULL, LBoxExceptionOutputFilter::CODE_BAD_PARAM);
		}
		$this->outputFilterClass = $outputFilterClass;
	}

	/**
	 * adds OutputFilter to item before returning
	 * @return AbstractRecordLBox
	 * @throws Exception
	 */
	public function current() {
		try {
			if (!($record = parent::current()) instanceof AbstractRecord) {
				return $record;
			}
			if (!($record = parent::current()) instanceof AbstractRecordLBox) {
				$class = get_class($record);
				throw new LBoxExceptionOutputFilter(LBoxExceptionOutputFilter::MSG_RECORD_ISNOT_ABSTRACTRECORDLBOX ." '$class' given.", LBoxExceptionOutputFilter::CODE_RECORD_ISNOT_ABSTRACTRECORDLBOX);
			}
			if (strlen($this->outputFilterClass) > 0) {
				$class = $this->outputFilterClass;
				$filter = new $class($record);
				$record->setOutputFilter($filter);
			}
			return $record;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizeno o mazani front cache
	 */
	protected function resetCache() {
		try {
			LBoxCacheManagerFront::getInstance()->cleanByRecordType($this->getClassVar("itemType"));
			return parent::resetCache();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizeno o mazani front cache
	 */
	public function clearCache() {
		try {
			LBoxCacheManagerFront::getInstance()->cleanByRecordType($this->getClassVar("itemType"));
			return parent::clearCache();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>