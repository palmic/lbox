<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
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

	/**
	 * @return bool
	 */
	public function valid() {
		return @array_key_exists($this->key, $this->items);
	}
}
?>