<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @date 2009-05-18
*/
class LBoxConfigItemLangdomain extends LBoxConfigItem
{
	protected $nodeName 			= "domain";
	protected $classNameIterator	= "LBoxIteratorLangdomains";
	
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