<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
abstract class LBoxOutputFilter
{
	/**
	 * @var OutputItem
	 */
	protected $instance;
	
	/**
	 * @param OutputItem $instance
	 * @throws LboxException
	 */
	public function __construct(OutputItem $instance) {
		$this->instance = $instance;
	}
	
	/**
	 * Zmeni predanou hodnotu podle potreb
	 * @param string $parName
	 * @param mixed $parValue
	 */
	abstract public function prepare($parName = "", $parValue = NULL);
}
?>