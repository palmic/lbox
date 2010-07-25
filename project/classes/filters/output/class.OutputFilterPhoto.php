<?
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2010-02-19
*/
class OutputFilterPhoto extends LBoxOutputFilter
{
	/**
	 * nazev property s cestou k adresari s fotkama
	 * @var string
	 */
	protected $propertyNamePath = "";
	
	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "url":
					$path	= $this->instance->getFilePath();
					$path	= str_replace("<project>", LBOX_PATH_PROJECT, $path);
					$path	= str_replace(LBOX_PATH_PROJECT, "", $path);
					return $path;
				break;
			default:
					return $value;
				break;
		}
	}
}
?>