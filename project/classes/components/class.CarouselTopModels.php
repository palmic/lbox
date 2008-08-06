<?
/**
 * breadcrumb navigation class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2007-07-30
*/
class CarouselTopModels extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
			$limit			= LBoxConfigManagerProperties::getPropertyContentByName("models_top_carousel_length");
			$records		= new PhotosRatingsTopRecords(false, array("rating" => 0, "votes" => 0), array(0, $limit));
			$TAL->photos	= $records;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>