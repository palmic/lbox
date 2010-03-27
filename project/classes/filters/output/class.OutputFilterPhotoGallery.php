<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class OutputFilterPhotoGallery extends LBoxOutputFilter
{
	/**
	 * name of config attribute referencing article displaying page via ID
	 * @var string
	 */
	protected $configVarNamePhotogalleryRefPage = "photogallery_ref_page";

	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "id":
					return $this->instance->getParamDirect("url");
					break;
				case "createdRSS":
					return gmdate("D, d M Y H:i:s", strtotime($this->instance->created)). " GMT";
					break;
				case "url":
					$refPageAttName	= $this->configVarNamePhotogalleryRefPage;
					// najdeme stranku zobrazovani fotogalerie podle reference
					$idPageItem					= LBoxConfigManagerProperties::getInstance()->getPropertyByName($refPageAttName)->getContent();
					$pageItem		 			= LBoxConfigManagerStructure::getInstance()->getPageById($idPageItem);
					$pageClass 					= $pageItem->class;
					if ($pageClass !== "PagePhotogallery") {
						throw new LBoxExceptionConfigStructure("Referenced 'photogallery' page (id=$idPageItem) defined in properties.xml like '$refPageAttName' is not 'PagePhotogallery' type, but '$pageClass'! Check it in structure config.");
					}
					$urlBase	= $this->instance->getParamDirect("url");
					return $pageItem->url .":$urlBase";
					break;
				default:
					return $value;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>