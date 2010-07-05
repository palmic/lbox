<?php
/**
 * @author Michal Palma <palmic@email.cz>
 * @package LBox
 * @version 1.0

 * @since 2007-12-08
 */
class OutputFilterDiscussionRecord extends OutputFilterRecordEditableByAdmin
{
	/**
	 * cache var
	 * @var array
	 */
	protected static $formsByIDs = array();
	
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "createdDate":
					return date("j.n. Y", $this->instance->created);
					break;
				case "createdDateTime":
					return date("j.n. Y H:m:s", $this->instance->created);
					break;
				case "url":
					if ($this->instance->getParamDirect("type") != "post") {
						return $this->getDiscussionPageUrl();
					}
					else {
						return $this->getDiscussionPageUrl() ."#discussion-post-". $this->instance->getParamDirect("id");
					}
					break;
				case "numPosts":
					return $this->instance->getDescendantsCount();
					break;
				case "postsWordDecl1":
						return $this->instance->getDescendantsCount() == 1;
					break;
				case "postsWordDecl2":
						return $this->instance->getDescendantsCount() > 1 && $this->instance->getDescendantsCount() < 5;
					break;
				case "postsWordDecl5":
						return $this->instance->getDescendantsCount() > 4 || $this->instance->getDescendantsCount() < 1;
					break;
				case "formToReply":
					if ($this->instance->hasParent()) {
						return $this->getFormToReply();
					}
					break;
				case "email":
						return (string)$value;
					break;
				case "www":
						if (strlen($value) > 0 && !preg_match("/^http(s?)\:\/\//", $value)) {
							return "http://$value";
						}
						else {
							return (string)$value;
						}
					break;
				default:
					return parent::prepare($name, $value);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci URL stranky/clanku/galerie, atd..  ke ktere je diskuze pripojena
	 * @return string
	 */
	protected function getDiscussionPageUrl() {
		try {
			$pageUrl	= LBoxConfigManagerStructure::getInstance()->getPageById($this->instance->pageId)->url;
			$paramUrl 	= $this->instance->urlParam;
			return $pageUrl . (strlen($paramUrl) > 0 ? ":$paramUrl" : "");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci formular - button na odpoved na prispevek
	 * @return LBoxForm
	 */
	protected function getFormToReply() {
		try {
			$classNameInstance	= get_class($this->instance);
			$idColName			= eval("return $classNameInstance::\$idColName;");
			if (array_key_exists($this->instance->$idColName, self::$formsByIDs)
				&& self::$formsByIDs[$this->instance->$idColName] instanceof LBoxForm) {
					return self::$formsByIDs[$this->instance->$idColName];
			}
			
			$controls["id"]		= new LBoxFormControlFillHidden("pid", "", $this->instance->$idColName);
			$controls["id"]		->setDisabled();
			
			$form	= new LBoxForm($this->instance->$idColName ."-to-reply", "post", "", "Odpovědět");
			$form	->setTemplateFileName("lbox_form_discussion_to_reply.html");
			//$form	->addProcessor(new LBoxFormProcessorDev);
			$form	->addProcessor(new ProcessorDiscussionToReply);
			foreach ($controls as $control) {
				$form->addControl($control);
			}
			return self::$formsByIDs[$this->instance->$idColName] = $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>