<?php
/**
 * Page classes protocol
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2007-12-08
*/
abstract class LBoxPage extends LBoxComponent
{
	protected $templatePath = LBOX_PATH_TEMPLATES_PAGES;

	/**
	 * component config node instance
	 * @var LBoxConfigItemComponent
	 */
	protected $config;

	/**
	 * @param string $templateFileName
	 * @throws
	 */
	public function __construct(LBoxConfigItemStructure $config) {
		$this->config = $config;
	}

	protected function executeStart() {
		try {
			// defaultne nastavime na config stranky OutputFilterPage
			$this->config->setOutputFilter(new OutputFilterPage($this->config));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * zdedena z LboxComponent
	 * prepsan zpusob identifikace, nelze pouzit id, protoze je ciselne - pole ve formulari musi byt stringove
	 * @return string
	 */
	public function getFormGroupName() {
		try {
			return __CLASS__;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * pridava parsing vysledku podle configu
	 * @param string $out
	 * @return string
	 * @throws Exception
	 */
	public function getContent() {
		try {
			$out	= parent::getContent();
			if ($this->isDebugOn()) {
				return $out;
			}
			$out 	= $this->removeComents($out);
			$out	= $this->compress($out);
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>