<?php 

interface IValidator {
	public function validate($value);
	public function setErrorMessage($errorMessage);
	public function chain(IValidator $validator);
	public function getErrorMessage();
	
}


// Base class implementation
abstract class Validator implements IValidator {
	protected $errorMessage;
	protected $next;
	public function __construct($errorMessage = '') {
	}
	
	public function chain (IValidator $validator) {
		$this->next = $validator;
		return $validator;
	}
	
	public function setErrorMessage($message) {
		$this->errorMessage = $message;
	}
	
	public function getErrorMessage() {
		return $this->errorMessage ? $this->errorMessage : get_class($this);
	}
}



?>