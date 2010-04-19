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
	CONST TYPE								= "richtext";
	CONST XT_FORM_CTRL_CLASSNAME			= "LBoxFormControlFill";
	CONST XT_FORM_CTRL_TEMPLATE_FILENAME	= "metanode_richtext.html";
	CONST TEMPLATE_FILENAME					= "metanode_richtext.html";

	/**
	 * pretizeno o TIDY cisteni
	 */
	public function setContent($content = "") {
		try {
			if (ini_get("magic_quotes_gpc")) {
				$content	= stripslashes($content);
			}
			if (function_exists("mb_convert_encoding") && function_exists("mb_convert_encoding")) {
				if (mb_detect_encoding($content) != "UTF-8") {
					$content	= mb_convert_encoding  ($content,  "UTF-8");
				}
			}
			if (class_exists("tidy")) {
				$tidyConfig = array('indent' => true,
									'output-xml' => false,
									'output-html' => false,
									'output-xhtml' => true,
									'show-body-only' => true,
									'wrap' => 200);
				$tidy	= new tidy();
				//var_dump($content);
				$content = $tidy->repairString($content, $tidyConfig, 'UTF8');
				//var_dump($content);die;
			}
			return parent::setContent($content);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>