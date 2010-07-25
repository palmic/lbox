<?
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2009-10-03
*/
class OutputFilterAuthDBFree extends LBoxOutputFilter
{
	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "nick":
					return $this->instance->getParamDirect("name");
				break;
			default:
					return $value;
		}
	}
}
?>