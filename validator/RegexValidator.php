<?php 
require_once('Validator.php');
class RegexValidator extends Validator {
	protected $errorMessage = "Regex does not much"; 
	
	public $regex = '';
	
	public function __construct($regex) {
		$this->regex = $regex;
	}
	
	public function validate($value) {
		return preg_match($value, $this->regex);
		
	}
	
	
	
}
?>