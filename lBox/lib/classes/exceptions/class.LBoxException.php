<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxException extends Exception
{
	const CODE_BAD_PARAM 			= 1;
	const CODE_BAD_CLASS_VAR		= 2;
	const CODE_BAD_INSTANCE_VAR		= 3;
	
	const MSG_PARAM_STRING_NOTNULL 				= "must be NOT-NULL string!";
	const MSG_PARAM_STRING_VALID_PATH_DIR		= "must be valid dir path!";
	const MSG_PARAM_STRING_VALID_PATH_FILE		= "must be valid file path!";
	const MSG_PARAM_ARRAY_NOTNULL 				= "must be NOT-NULL array!";
	const MSG_PARAM_INT_NOTNULL 				= "must be integer > 0!";
	const MSG_PARAM_INSTANCE_CONCRETE			= "must be concrete class instance!";
	
	const MSG_PARAM_INT 						= "must be integer!";
	const MSG_PARAM_BOOL 						= "must be boolean!";
	
	const MSG_INSTANCE_VAR_STRING_NOTNULL		= "Bad instance param, must be NOT NULL string!";

	/**
	 * default destination email addresses for error notification
	 * @var string
	 */
	protected $defaultEmails = array(
									"palmic@email.cz"
                                     );

	/**
	 * log file name whithout path
	 * @var string
	 */
	protected $logFilename = "error.log";

	/**
	 * log file dir relative to script calling this class
	 * @var string
	 */
	protected $logFileDir = "";
	
	/**
	 * file logging on/off
	 * @var bool
	 */
	protected $logVerbose	= true;

	/**
	 * log file handler
	 * @var resource
	 */
	protected $logFileH;


	public function __construct($message = "", $code = 0) {
		parent::__construct($message, $code);
		$this->log();
		//$this->send();
	}


	// encapsulated methods --------------------------------------------------------------------------------------------------------------------

	/**
	 * returns logFilename with YYYY-MM date on start
	 */
	protected function getLogFilePath() {
		$month = date("Y-m");
		return $this->logFileDir . $month ."_". $this->logFilename;
	}

	protected function getFileH() {
		if (is_resource($this->logFileH)) {
			return $this->logFileH;
		}
		return $this->logFileH = fopen($this->getLogFilePath(), "a+");
	}

	/**
	 * log error
	 * @param string message - message to log
	 */
	protected function log($message = "") {
		if (!$this->logVerbose) {
			return;
		}
		if (strlen($message) < 1) {
			$message  = $this->getLogMessage();
		}
		fwrite($this->getFileH(), $message);
	}

	/**
	 * send message to given mail or to default mail address
	 * @param string message - message to send
	 * @param string to - destination mail address
	 */
	protected function send($message = "", $to = array()) {
		if (is_numeric(strpos($_SERVER["SERVER_NAME"], "dev."))) {
			return;
		}
		if (is_numeric(strpos($_SERVER["SERVER_NAME"], "localhost"))) {
			return;
		}

		if (strlen($message) < 1) {
			$trace   = $this->getTraceAsString();
			$message = $this->getMessage() ."\n\n$trace\n\nPlease check log file ". $this->getLogFilePath();
		}
		if (empty($to)) {
			$to = $this->defaultEmails;
		}
		$subLength   = 15;
		$subject     = strlen($this->getMessage()) > $subLength ? substr($this->getMessage(), 0, $subLength) ."..." : $this->getMessage();
		$from        = "class.".get_class($this)."@". $_SERVER["HTTP_HOST"];
		$headers 	 = "";
		$headers 	.= "MIME-Version: 1.0\n";
		$headers    .= "From: $from\n";
		$headers 	.= "X-Mailer: PHP\n"; // mailový klient
		$headers	.= "Content-Transfer-Encoding: base64\n";
		foreach($to as $address) {
			mail($address, $subject, base64_encode($message), $headers);
		}
	}

	/**
	 * creates and returns log message from exception message, text and more
	 * @returns string
	 */
	protected function getLogMessage() {
		$msg        = "";
		$time       = date("Y-m-d H:i:s");
		$msgHeader  = $time;
		$msgFooter  = "---------------------------------------------------------------------------------------------------------";
		$msg .= $msgHeader;
		$msg .= "\n";
		$msg .= "Code: ". $this->getCode();
		$msg .= "\n";
		$msg .= $this->getMessage();
		$msg .= "\n";
		$msg .= "Thrown by: '". $this->getFile() ."'";
		$msg .= "\n";
		$msg .= "on line: '". $this->getLine() ."'.";
		$msg .= "\n";
		$msg .= $this->getTraceAsString();
		$msg .= "\n";
		$msg .= $msgFooter;
		$msg .= "\n";
		$msg .= "\n";
		return $msg;
	}
}
?>