<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-15
*/
class OutputFilterArticleRSS extends OutputFilterArticle
{
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "perex":
				case "body":
					return trim(strip_tags($this->instance->getParamDirect($name)));
					break;
				case "publishedRSS":
					return gmdate("D, d M Y H:i:s", strtotime($this->instance->published)). " GMT";
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