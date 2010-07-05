<?php
class ProcessorDiscussionPost extends LBoxFormProcessor
{
	/**
	 * pattern na URL param reply to
	 * @var string
	 */
	protected $patternURLParamReplyTo	= "^replyto\-([\w\d]+)$";
	
	public function process() {
		try {
			$parent				= new DiscussionsRecord($this->form->getControlByName("pid")->getValue());
			$record				= new DiscussionsRecord;
			$classNameRecord	= get_class($record);

			foreach ($this->form->getControls() as $control) {
				if ($control instanceof LBoxFormControlMultiple) continue;
				if ($control instanceof LBoxFormControlSpamDefense) continue;
				if ($control->getName() == "pid") continue;
				if ($control->getName() == eval("return $classNameRecord::\$idColName;")) continue;
				$colName	= $control->getName();
				$record	->$colName	= strlen($control->getValue()) > 0 ? $control->getValue() : "<<NULL>>";
			}
			$record->pageId	= LBoxFront::getPage()->id;
						
			$record->store();
			$parent->addChild($record);
			
			// u odpovedi reloadovat na hlavni vlakno
			if ($parent->hasParent()) {
				LBoxFront::reload(LBoxUtil::getURLWithoutParamsByPattern(array("/". $this->patternURLParamReplyTo ."/")));
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>