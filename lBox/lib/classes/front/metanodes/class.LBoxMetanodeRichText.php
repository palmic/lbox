<?
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @date 2009-06-06
 */
class LBoxMetanodeRichText extends LBoxMetanodeString
{
	protected $ext							= "html";
	
	CONST XT_FORM_CTRL_CLASSNAME			= "LBoxFormControlFill";
	CONST XT_FORM_CTRL_TEMPLATE_FILENAME	= "metanode_richtext.html";
	CONST XT_FORM_VALIDATOR_CLASSNAME		= "ValidatorMetanodeRichText";
	CONST XT_FORM_FILTER_CLASSNAME			= "LBoxFormFilterMetanodeRichText";
}
?>