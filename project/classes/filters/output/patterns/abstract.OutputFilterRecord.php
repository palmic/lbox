<?
/**
* @author Michal Palma <michal.palma@gmail.com>
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
	 * explicitni definice nazvu atributu editovaneho recordu podle ktereho ho bude stranka s detailem hledat
	 * @var string
	 */
	protected $editURLFilterColname			= "";

	public function prepare($name = "", $value = NULL) {
		try {
			$classNameInstance		= get_class($this->instance);
			$editURLFilterColname	= strlen($this->editURLFilterColname) > 0 ? $this->editURLFilterColname : eval("return $classNameInstance::\$idColName;");
			switch ($name) {
				case "url_detail":
						if (strlen($this->propertyNameRefPageDetail) < 1) {
							return $this->instance->getParamDirect($this->editURLFilterColname);
						}
						return LBoxConfigManagerStructure::getInstance()
									->getPageById(LBoxConfigManagerProperties::getPropertyContentByName($this->propertyNameRefPageDetail))->url
								. ":"
								. $this->instance->getParamDirect($this->editURLFilterColname);
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