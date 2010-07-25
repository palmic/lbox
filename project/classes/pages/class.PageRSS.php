<?php
/**
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox techhouse.cz
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @since 2007-12-15
 */
abstract class PageRSS extends LBoxPage implements OutputItem
{
	/**
	 * Config instance caller
	 * @param string $name
	 * @param array $args
	 */
	public function __call($name, $args) {
		try {
			//TODO dorazit evalem pro pripad volani s parametry
			if (count($args) < 1) {
				return $this->config->$name();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci url params diskuze - array(pageDiskuze->id, urlParamDiskuze) (napriklad /rss/diskuze:14/paroubek-smrdi vrati array(14, "paroubek-smrdi"))
	 * (pokud jsou samozrejme kompletni)
	 * - public kuli output filteru
	 * @return array
	 * @throws LBoxException
	 */
	public function getDiscussionURLParamsArray() {
		try {
			$out = array();
			foreach ($this->getUrlParamsArray() as $param) {
				if ($this->isUrlParamPaging($param)) continue;
				$out[] = $param;
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * nastavime XML header
	 */
	protected function executeStart() {
		try {
			parent::executeStart();
			$this->config->setOutputFilter(new OutputFilterPageRSS($this));
			header('Content-Type: application/xml');
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * implementovano kuli OutputItem. Na PageRSS instance totiz musime kuli nekterym funcim implementovat OutputFilter primo
	 * @throws LBoxException
	 */
	public function getParamDirect($name = "") {
		try {
			return $this->config->getParamDirect();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>