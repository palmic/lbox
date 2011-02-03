<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @date 2011-02-03
*/
class LBoxConfigItemFront extends LBoxConfigItem
{
	protected $nodeName 			= "property";
	protected $classNameIterator	= "LBoxIteratorConfigFront";
	
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