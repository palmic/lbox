<?php
/**
 * vzor pro processory editujici record
 */
abstract class ProcessorRecordEdit extends LBoxFormProcessor
{
	/**
	 * typ recordu
	 * @var string
	 */
	protected $classNameRecord	= "";
	
	/**
	 * cache var
	 * @var AbstractRecordLBox
	 */
	protected $record;
	
	public function process() {
		try {
			if (strlen($classNameRecord = $this->classNameRecord) < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_INSTANCE_VAR);
			}
			$record		= $this->getRecord();
			foreach ($this->form->getControls() as $control) {
				if ($control instanceof LBoxFormControlMultiple) continue;
				if ($control instanceof LBoxFormControlSpamDefense) continue;
				if ($control->getName() == eval("return $classNameRecord::\$idColName;")) continue;
				if ($control->getName() == "filter_by") continue;
				$colName	= $control->getName();
				$record	->$colName	= strlen($control->getValue()) > 0 ? $control->getValue() : "<<NULL>>";
			}
			$record->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na relevantni record
	 * @return AbstractRecordLBox
	 */
	protected function getRecord() {
		try {
			if ($this->record instanceof AbstractRecordLBox) {
				return $this->record;
			}
			$controls			= $this->form->getControls();
			$classNameRecord	= $this->classNameRecord;
			$idColName			= eval("return $classNameRecord::\$idColName;");
			$classNameRecords	= eval("return $classNameRecord::\$itemsType;");
			if (array_key_exists("filter_by", $controls)) {
				if (strlen($this->form->getControlByName("filter_by")->getValue()) > 0) {
					$idColName	= $this->form->getControlByName("filter_by")->getValue();
				}
				$filterBy	= $controls["filter_by"]->getValue();
			}
			else {
				$filterBy	= $idColName;
			}
			$filter		= array($filterBy => $this->form->getControlByName($idColName)->getValue());
			$records	= new $classNameRecords($filter);
			if ($records->count() > 0) {
				return $this->record = $records->current();
			}
			else {
				return $this->record = new $classNameRecord;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>