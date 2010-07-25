<?php
/**
 * interface pro tridy pouzivane ve spojitosti s output filterem
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @date 2007-12-08
*/
interface OutputItem
{
	/**
	 * getter for filtered param
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name = "");

	/**
	 * setter output filteru
	 * @param LBoxOutputFilter $outputFilter
	 */
	public function setOutputFilter(LBoxOutputFilter $outputFilter);

	/**
	 * getter for unfiltered param
	 * @param string $name 
	 * @return mixed
	 * @throws Exception
	 */
	public function getParamDirect($name = "");
}
?>