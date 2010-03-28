<?php
/**
 * @author Michal Palma <palmic@email.cz>
 * @package LBox
 * @version 1.0
 * @since 2010-01-12
 */
class OutputFilterDiscussionNonstopCity extends OutputFilterDiscussion
{
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "created_human":
						return date("j.n.Y H:i:s", strtotime($this->instance->getParamDirect("created")));
					break;
				case "is_reply":
						return $this->instance->hasParent() ? ($this->instance->getParent()->type == $this->instance->getParamDirect("type")) : false;
					break;
				default:
					return parent::prepare($name, $value);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>