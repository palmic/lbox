<?php
class LBoxFormFilterTidy extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			$value	= $control->getValue();
			if (class_exists("tidy")) {
				$tidyConfig = array('indent' => true,
									'output-xml' => false,
									'output-html' => false,
									'output-xhtml' => true,
									'show-body-only' => true,
									'wrap' => 200);
				$tidy	= new tidy();
				//var_dump($content);
				$value = $tidy->repairString($value, $tidyConfig/*, 'UTF8'*/);
				//var_dump($content);die;
			}
			return $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>