<?php

/**
 * adds LBoxOutputFilter compatibility
 * @author Michal Palma <palmic at email dot cz>
 * @date 2007-11-03
 */
abstract class AbstractRecordsLBox extends AbstractRecords
{
	/**
	 * items OutputFilter class
	 * @var string
	 */
	protected $outputFilterClass;

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
			// do inform front cache manager that this record type data hapen to be used at this URL
			LBoxCacheManagerFront::getInstance()->addRecordType($this->getClassVar("itemType"));
			
			if (!($record = parent::current()) instanceof AbstractRecord) {
				return $record;
			}
			if (!($record = parent::current()) instanceof AbstractRecordLBox) {
				throw new LBoxExceptionOutputFilter(LBoxExceptionOutputFilter::MSG_RECORD_ISNOT_ABSTRACTRECORDLBOX, LBoxExceptionOutputFilter::CODE_RECORD_ISNOT_ABSTRACTRECORDLBOX);
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