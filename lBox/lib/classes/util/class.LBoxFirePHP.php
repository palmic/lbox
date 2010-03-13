<?php
/**
 * class transparing firePHP commands
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2009-06-12
*/
class LBoxFirePHP
{
	public static function log($message = "") {
		try {
			$trace=debug_backtrace();
			$calledFile	= $trace[0]["file"];
			$calledLine	= $trace[0]["line"];
			return FirePHP::getInstance(true)->log("$message ($calledFile:$calledL)");
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public static function warn($message = "") {
		try {
			return FirePHP::getInstance(true)->warn($message);
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public static function error($message = "") {
		try {
			return FirePHP::getInstance(true)->error($message);
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public static function table($tableData = array(), $tableCaption = "data") {
		try {
			$table[]   = array("key", "value");
			foreach ($tableData as $k => $v) {
				$table[] = array($k,$v);
			}
			return FirePHP::getInstance(true)->table($tableCaption, $table);
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public static function throwException(Exception $e) {
		try {
			self::error('Exception of code:  '. $e->getCode() .' thrown with the message: '. $e->getMessage() . '');
			self::warn('Thrown by: :  '. $e->getFile());
			self::warn('At line: :  '. $e->getLine());
			$i = 1;
			foreach ($e->getTrace() as $traceStep) {
				$traceLine	= array();
				foreach ($traceStep as $attName => $attValue) {
					$traceParams[]	= $attName;
					$traceLine[]	= $attValue;
				}
				if ($i < 2) { $trace[0]	= $traceParams; }
				$trace[$i]	= $traceLine;
				$i++;
			}
			FirePHP::getInstance(true)->table('Stack trace', $trace);
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}
?>