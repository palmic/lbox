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
	
	CONST TYPE								= "richtext";
	CONST XT_FORM_CTRL_CLASSNAME			= "LBoxFormControlFill";
	CONST XT_FORM_CTRL_TEMPLATE_FILENAME	= "metanode_richtext.html";
	CONST TEMPLATE_FILENAME					= "metanode_richtext.html";

	/**
	 * pretizeno o TIDY cisteni
	 */
	public function setContent($content = "") {
		try {
			if (function_exists("tidy_parse_string")) {
				$tidyConfig = array('indent' => false,
									'output-xhtml' => false,
                					'wrap' => 200);
				$tidy	= tidy_parse_string($buffer, $config, 'UTF8');
				$tidy->cleanRepair();
				$content	= (string)$tidy;
			}
			return parent::setContent($content);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>