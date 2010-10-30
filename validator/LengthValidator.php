<?php
// base
require_once('Validator.php');

class LengthValidator extends Validator {
	
	public $min = 1;
	public $max = 255;
	
	
	public function __construct($min=1, $max=255) {
		if ( ! $min && ! $max) {
			throw new Exception ("Please specify the minimum or maximum value.");
		}
		parent::__construct($min, $max);
		$this->min = $min;
		$this->max = $max;	
	}
	
	public function setMin($min) {
		$this->min = $min;
	}
	public function setMax($max) {
		$this->max = $max;
	}
	
	public function getErrorMessage() {
		if ($this->max && $this->min) {
			return "Between " . $this->min . " and " . $this->max . " characters";
		} if ($this->max) {
			return "At least " . $this->max . " characters";
		} 
	}
	
	public function getJS($js) {
		
		$js['message']['minlength'] = $this->getErrorMessage();
		$js['rule']["minlength"] = $this->min;
		return $js;
	}
	
	public function validate($value) {
		if ( (strlen($value) >= $this->min) && (strlen($value) <=$this->max)) {
			return true;
		} 
		return false;
	}
}