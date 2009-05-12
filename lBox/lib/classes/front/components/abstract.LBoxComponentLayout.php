<?
/**
 * Layout class to define some parameters
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
abstract class LBoxComponentLayout extends LBoxComponent
{
	const DEBUG = false;
	
	protected $templatePath = LBOX_PATH_TEMPLATES_LAYOUTS;

	/**
	 * pridava parsing vysledku podle configu
	 * @param string $out
	 * @return string
	 * @throws Exception
	 */
	public function getContent() {
		try {
			$out	= parent::getContent();
			if ($this->isDebugOn()) {
				return $out;
			}
			$out 	= $this->removeComents($out);
			$out	= $this->compress($out);
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>