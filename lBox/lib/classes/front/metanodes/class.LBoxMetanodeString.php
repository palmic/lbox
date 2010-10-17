<?
/**
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @date 2009-06-06
 */
class LBoxMetanodeString extends LBoxMetanode
{
	CONST TYPE								= "string";
	CONST XT_FORM_CTRL_CLASSNAME			= "LBoxFormControlFill";
	CONST XT_FORM_CTRL_TEMPLATE_FILENAME	= "metanode_string.html";
	CONST TEMPLATE_FILENAME					= "metanode_string.html";
	
	/**
	 * pretizeno o cisteni elementu
	 */
	public function getContent() {
		try {
			if (get_class() == get_class($this)) {
				return strip_tags(parent::getContent());
			}
			else {
				return parent::getContent();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>