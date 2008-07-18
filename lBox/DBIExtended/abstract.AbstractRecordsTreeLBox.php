<?php

/**
 * adds LBoxOutputFilter compatibility
 * @author Michal Palma <palmic at email dot cz>
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
}
?>