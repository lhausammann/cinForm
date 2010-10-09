<?php 

interface IValidator {
	public function validate($value);
	public function setErrorMessage($errorMessage);
	public function getErrorMessage();
	
}


// Base class implementation
abstract class Validator implements IValidator {
	protected $errorMessage = "";
	
	
	public function __construct($errorMessage = '') {
		$this->errorMessage = $errorMessage;
	}
	
	public function setErrorMessage($message) {
		$this->errorMessage = $message;
	}
	
	public function getErrorMessage() {
		echo "error msg: " + $this->errorMessage;
		return $this->errorMessage=='' ? $this->errorMessage : get_class($this);
	}
}



?>