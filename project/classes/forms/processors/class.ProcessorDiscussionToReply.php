<?php
class ProcessorDiscussionToReply extends LBoxFormProcessor
{
	protected $patternURLParamReplyTo	= "^replyto\-([\w\d]+)$";
	
	public function process() {
		try {
			$pid	= $this->form->getControlByName("pid")->getValue();
			LBoxFront::reload(LBoxUtil::getURLWithParams(array("replyto-$pid"), LBoxUtil::getURLWithoutParamsByPattern(array("/". $this->patternURLParamReplyTo ."/"))) ."#frm-discussion-$pid-post");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>