<?
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-12-15
*/
class OutputFilterDiscussionPostRSS extends OutputFilterDiscussion
{
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "body":
					return trim(strip_tags($this->instance->getParamDirect($name)));
					break;
				case "createdRSS":
					return gmdate("D, d M Y H:i:s", strtotime($this->instance->created)). " GMT";
					break;
				default:
					return parent::prepare($name, $value);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>