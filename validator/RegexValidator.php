<?php 
require_once('Validator.php');
class RegexValidator extends Validator {
	protected $errorMessage = "Regex does not match"; 
	
	public $regex = '';
	
	public function __construct($regex, $message = 'Regex does not match') {
		$this->regex = $regex;
		$this->message = $message;
	}
	
	public function validate($value) {
		return (preg_match($this->regex, $value) > 0) ? true : false;
		
		
	}
}
?>