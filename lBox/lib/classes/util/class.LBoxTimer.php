<?php
class LBoxTimer
{
	protected static $instance;
	
	/**
	 * time of instance creation
	 * @var float
	 */
	protected $timeBird;
	
	protected function __construct() {
		$this->timeBird	= microtime(true);
	}

	/**
	 * vraci cas dosavadni existence sve momentalni instance
	 * @return float
	 */
	public function getTimeOfLife() {
		try {
			return microtime(true) - $this->timeBird;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return LBoxTimer
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
}
?>