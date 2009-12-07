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
	 * ignored controls names
	 * @var array
	 */
	protected $ignoredControls		= array();
	
	/**
	 * nove vytvoreny/ulozeny record
	 * @var AbstractRecordLBox
	 */
	protected $recordSaved;
	
	public function process() {
		try {
			if (strlen($classNameRecord = $this->classNameRecord) < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_INSTANCE_VAR);
			}
			$record		= $this->getRecord();
			foreach ($this->form->getControls() as $control) {
				if (is_numeric(array_search($control->getName(), $this->ignoredControls))) {
					continue;
				}
				if ($control instanceof LBoxFormControlMultiple) continue;
				if ($control instanceof LBoxFormControlSpamDefense) continue;
				if ($control->getName() == eval("return $classNameRecord::\$idColName;")) continue;
				if ($control->getName() == "filter_by") continue;
				// prepinac podle typu controlu
				switch (true) {
					case ($control instanceof LBoxFormControlBool):
							$value	= (int)$control->getValue();
						break;
					default:
							$value	= $control->getValue();
				}
				$colName	= $control->getName();
				$record	->$colName	= strlen($value) > 0 ? $value : "<<NULL>>";
			}
			$record	->store();
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
			$controls			= $this->form->getControls();
			$classNameRecord	= $this->classNameRecord;
			if ($this->recordSaved instanceof $classNameRecord) {
				return $this->recordSaved;
			}
			$idColName			= eval("return $classNameRecord::\$idColName;");
			$classNameRecords	= eval("return $classNameRecord::\$itemsType;");
			if (array_key_exists("filter_by", $controls)) {
				if (strlen($this->form->getControlByName($idColName)->getValue()) > 0) {
					$records	= new $classNameRecords(array($controls["filter_by"]->getValue() => $this->form->getControlByName($idColName)->getValue()));
					if ($records->count() < 1) {
						throw new LBoxExceptionFormProcessor("Record not found!");
					}
					return $this->recordSaved = $records->current();
				}
			}
			return $this->recordSaved = new $classNameRecord(strlen($this->form->getControlByName($idColName)->getValue()) > 0 ? $this->form->getControlByName($idColName)->getValue() : NULL);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>