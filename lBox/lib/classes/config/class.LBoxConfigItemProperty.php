<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxConfigItemProperty extends LBoxConfigItem
{
	protected $nodeName 			= "property";
	protected $classNameIterator	= "LBoxIteratorProperties";
	
	public function __construct() {
		try {
			// defaultne nastavime OutputFilterComponent
			// $this->setOutputFilter(new OutputFilterComponent($this));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>