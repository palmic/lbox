<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2009-10-03
*/
class LBoxConfigItemAuthDBFree extends LBoxConfigItem
{
	/**
	 * page attributes names
	 * @var string
	 */
	protected $attNames = array(
								"name" 		=> "name",
								"password"	=> "password",
	);

	protected $nodeName 			= "login";
	protected $classNameIterator	= "LBoxIteratorAuthDBFree";

	public function __construct() {
		try {
			// nastavim defaultni outputfilter
			$this->setOutputFilter(new OutputFilterAuthDBFree($this));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>