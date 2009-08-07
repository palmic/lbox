<?
/**
 * breadcrumb navigation class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-08-07
*/
class LBoxComponentMetanodeCaller extends LBoxComponent
{
	/**
	 * pretizeno - ubran parametr page
	*/
	public function __construct(LBoxConfigItemComponent $config) {
		$this->config 	= $config;
	}

	protected function executePrepend(PHPTAL $TAL) {
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>