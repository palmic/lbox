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
//DbControl::$debug	= true;
			$limit			= LBoxConfigManagerProperties::getPropertyContentByName("models_top_carousel_length");
			$records		= new PhotosRatingsTopRecords(false, array("rating" => 0, "votes" => 0), array(0, $limit));
			$records->setIsTree(false);
			$TAL->photos	= $records;
			$TAL->itemSizeX	= LBoxConfigManagerProperties::getPropertyContentByName("models_top_carousel_item_size_x");
			$TAL->itemSizeY	= LBoxConfigManagerProperties::getPropertyContentByName("models_top_carousel_item_size_y");
			$TAL->imgSizeX	= LBoxConfigManagerProperties::getPropertyContentByName("models_top_carousel_img_size_x");
			$TAL->imgSizeY	= LBoxConfigManagerProperties::getPropertyContentByName("models_top_carousel_img_size_y");
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function getContent() {
		try {
			try {
				return parent::getContent();
			}
			catch (LBoxExceptionFilesystem $e) {
				//if ($e->getCode() == )
				/*else*/ throw $e;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>