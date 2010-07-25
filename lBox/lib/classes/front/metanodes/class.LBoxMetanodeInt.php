<?
/**
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @date 2009-06-06
 */
class LBoxMetanodeInt extends LBoxMetanode
{
	CONST TYPE								= "int";
	CONST XT_FORM_CTRL_CLASSNAME			= "LBoxFormControlFill";
	CONST XT_FORM_CTRL_TEMPLATE_FILENAME	= "metanode_int.html";
	CONST TEMPLATE_FILENAME					= "metanode_int.html";
		
	public function getContent() {
		try {
			if (strlen($content = parent::getContent()) > 0) {
				/*if (!is_numeric($content)) {
					throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_NODECONTENT_NOT_NUMERIC, LBoxExceptionMetanodes::CODE_NODECONTENT_NOT_NUMERIC);
				}*/
				return (int)$content;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function setContent($content = "") {
		try {
			if (strlen($content) > 0) {
				if (!is_numeric($content)) {
					throw new LBoxExceptionMetanodes("\$content: ". LBoxExceptionMetanodes::MSG_NODECONTENT_NOT_NUMERIC, LBoxExceptionMetanodes::CODE_NODECONTENT_NOT_NUMERIC);
				}
			}
			parent::setContent((int)$content);
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}
?>