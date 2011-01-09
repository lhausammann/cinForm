<?php 
class Validator_Regex extends Validator_Base {
	protected $errorMessage = "Regex does not match"; 
	protected $name='regex';
	public $regex = '';
	
	public function __construct($regex, $message = 'Regex does not match') {
		$this->regex = $regex;
		$this->errorMessage = $message;
	}
	
	public function getJs($js) {
		$js["message"]["regex"] = $this->getErrorMessage();
		// php regexes start and ends with /, but javascript regex must not:
		$regex = trim($this->regex, '/');
		$js["rule"]["regex"] = $regex;
		return $js;
	}
	
	public function validate($value) {
		return (preg_match($this->regex, $value) > 0) ? true : false;
	}
}