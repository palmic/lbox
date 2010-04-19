<?php
class LBoxFormFilterTidy extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			$value	= $control->getValue();
			if (function_exists("mb_convert_encoding") && function_exists("mb_convert_encoding")) {
				if (mb_detect_encoding($value) != "UTF-8") {
					$value	= mb_convert_encoding  ($value,  "UTF-8");
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
//var_dump($value);
				$value = $tidy->repairString($value, $tidyConfig, 'UTF8');
//var_dump($value);die;
			}
			return $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>