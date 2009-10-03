<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-06
*/
class ListWinners extends ListSchools
{
	protected $formProcessorClassName	= "ProcessorFilterListWinners";

	protected function executePrepend(PHPTAL $TAL) {
		try {
			parent::executePrepend($TAL);
			$TAL->winners	= $this->getWinners();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function getWinners() {
		try {
			//TODO doresit jak se maji vypisovat vyherci
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>