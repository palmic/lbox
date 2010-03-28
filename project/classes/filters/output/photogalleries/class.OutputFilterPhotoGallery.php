<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class OutputFilterPhotoGallery extends OutputFilterRecordEditableByAdmin
{
	protected $propertyNameRefPageEdit		= "ref_page_xt_edit_photogalleries";
	
	/**
	 * name of config attribute referencing article displaying page via ID
	 * @var string
	 */
	protected $configVarNamePhotogalleryRefPage = "ref_page_detail_photogalleries";

	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "id":
					return $this->instance->getParamDirect("url");
					break;
				case "heading":
					return $this->instance->getParamDirect("name");
					break;
				case "createdRSS":
					return gmdate("D, d M Y H:i:s", strtotime($this->instance->created)). " GMT";
					break;
				case "url":
					$refPageAttName	= $this->configVarNamePhotogalleryRefPage;
					// najdeme stranku zobrazovani fotogalerie podle reference
					$idPageItem					= LBoxConfigManagerProperties::getInstance()->getPropertyByName($refPageAttName)->getContent();
					$pageItem		 			= LBoxConfigManagerStructure::getInstance()->getPageById($idPageItem);
					$urlBase	= $this->instance->getParamDirect("url");
					return $pageItem->url .":$urlBase";
					break;
				case "published_human_cs":
						return date("j.n.Y", $this->instance->__get("time_published"));
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