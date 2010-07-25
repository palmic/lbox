<?php
/**
 * Page classes protocol
* @author Michal Palma <michal.palma@gmail.com>
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

	/**
	 * pretizeno o automaticke vytvareni sablony z defaultni
	 * @return string
	 */
	protected function getTemplatePath() {
		try {
			$pathTemplate	= parent::getTemplatePath();
			if (!file_exists($pathTemplate)) {
				$srcPath	= LBoxConfigSystem::getInstance()->getParamByPath("pages/templates/path") .SLASH. LBoxConfigSystem::getInstance()->getParamByPath("pages/templates/default");
				LBoxUtil::copyFile($srcPath, $pathTemplate);
			}
			return $pathTemplate;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizeno - stranka samozrejme sama sebe k sobe indexovat nebude 
	 */
	protected function addUsageToCache() {
		try {
			//NULL
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>