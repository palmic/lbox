<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxSlotManager extends LBox
{
	/**
	 * singleton instance
	 * @var LBoxSlotManager
	 */
	protected static $instance;
	
	/**
	 * array of defined slots
	 * @var array
	 */
	protected $slots = array();
	
	/**
	 * Setter na slot
	 * @param string $name
	 * @return string
	 * @throws LBoxExceptionFront
	 */
	public function setSlot($name = "", $value = "") {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionFront("\$name". LBoxExceptionFront::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFront::CODE_BAD_PARAM);
			}
			if (array_key_exists($name, $this->slots)) {
				throw new LBoxExceptionFront("'$name' ". LBoxExceptionFront::MSG_SLOT_DEFINED, LBoxExceptionFront::CODE_SLOT_DEFINED);
			}			
			$this->slots[$name] = $value;
		}
		catch (Exception $e) {
			throw $e;
		}		
	}
	
	/**
	 * Getter na obsah ulozeneho slotu
	 * @param string $name
	 * @return string
	 * @throws Exception
	 */
	public function getSlot($name = "") {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionFront(LBoxExceptionFront::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFront::CODE_BAD_PARAM);
			}
			if (!array_key_exists($name, $this->slots)) {
				throw new LBoxExceptionFront("'$name' ". LBoxExceptionFront::MSG_SLOT_NOT_DEFINED, LBoxExceptionFront::CODE_SLOT_NOT_DEFINED);
			}			
			return $this->slots[$name];
		}
		catch (Exception $e) {
			throw $e;
		}		
	}

	/**
	 * @return LBoxSlotManager
	 * @throws Exception
	 */
	public static function getInstance() {
		$className 	= __CLASS__;
		try {
			if (!self::$instance instanceof $className) {
				self::$instance = new $className;
			}
			return self::$instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function __construct() {}
}
?>