<?
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2008-05-08
*/
class OutputFilterArticleNewsStatic extends OutputFilterPage
{
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "title_image_url":
					$pathTitleImages	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("articles_static_title_images_path")->getContent();
					return "$pathTitleImages/$value";
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