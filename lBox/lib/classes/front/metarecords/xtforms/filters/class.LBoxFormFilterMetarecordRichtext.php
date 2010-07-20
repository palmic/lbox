<?php
class LBoxFormFilterMetarecordRichtext extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			$content	= $control->getValue();
			if (ini_get("magic_quotes_gpc")) {
				$content	= stripslashes($content);
			}
			if (class_exists("tidy")) {
				$tidyConfig = array('indent' => true,
									'output-xml' => false,
									'output-html' => false,
									'output-xhtml' => true,
									'show-body-only' => true,
									'clean' => true,
									'wrap' => 200);
				$tidy	= new tidy();
				//var_dump($content);
				$content = $tidy->repairString($content, $tidyConfig, 'UTF8');
				//var_dump($content);die;
			}
			return $content;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>