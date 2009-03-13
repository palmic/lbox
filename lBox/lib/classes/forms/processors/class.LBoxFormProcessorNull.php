<?php
/**
 * processor, ktery slouzi k uspokojeni formu pri odesilani, aniz by neco delal (form bez processoru vyhazuje vyjimku)
 */
class LBoxFormProcessorNull extends LBoxFormProcessor
{
	public function process() {
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>