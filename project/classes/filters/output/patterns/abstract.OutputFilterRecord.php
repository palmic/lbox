<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-10-16
*/
abstract class OutputFilterRecord extends LBoxOutputFilter
{
	/**
	 * musi byt nastaveno konkretnim podedenym OF
	 * @var string
	 */
	protected $propertyNameRefPageDetail		= "";

	/**
	 * explicitni definice nazvu atributu editovaneho recordu podle ktereho ho bude stranka s editaci hledat
	 * @var string
	 */
	protected $editURLFilterColname			= "";

	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "url_detail":
						if (strlen($this->propertyNameRefPageDetail) < 1) {
							return $this->instance->getParamDirect("url");
						}
						return LBoxConfigManagerStructure::getInstance()
									->getPageById(LBoxConfigManagerProperties::getPropertyContentByName($this->propertyNameRefPageDetail))->url
								. ":"
								. $this->instance->getParamDirect("url");
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