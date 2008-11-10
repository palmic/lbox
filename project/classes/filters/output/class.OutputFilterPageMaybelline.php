<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-11
*/
class OutputFilterPageMaybelline extends OutputFilterPage
{
	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "bodyclass":
					return $value ? $value : "";
				break;
			case "headingSub":
					return "";
				break;
			case "isCurrent":
					if ($this->instance->getParamDirect("is_passive")) {
						return true;
					}
					return parent::prepare($name, $value);
				break;
			default:
				return parent::prepare($name, $value);
		}
	}
}
?>