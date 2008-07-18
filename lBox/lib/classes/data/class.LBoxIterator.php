<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
abstract class LBoxIterator implements Iterator
{
	/**
	 * items array
	 * @var array
	 */
	protected $items = array();

	/**
	 * current item key
	 * @var int
	 */
	protected $key = 0;
	
	/**
	 * vraci current item
	 * @return mixed
	 */
	public function current() {
		return $this->items[$this->key()];
	}

	/**
	 * vraci current key
	 * @return int
	 */
	public function key() {
		return $this->key;
	}

	/**
	 * Posouva key na dalsi
	 * @return void
	 */
	public function next() {
		$this->key++;
	}

	/**
	 * Vynuluje key
	 * @return void
	 */
	public function rewind() {
		$this->key = 0;
	}
}
?>