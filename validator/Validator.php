<?php 

interface IValidator {
	public function validate($value);
	public function setErrorMessage($errorMessage);
	public function getErrorMessage();
	
	// return the client-side validation javascript as array.
	// TODO: rename to buildJs, because js is returned.
	public function getJS($js);
	
	
	
}


// Base class implementation
abstract class Validator implements IValidator {
	
	
	protected $errorMessage;
	protected $name;
	public function __construct($errorMessage = '') {
		if ($this->errorMessage)
			$this->errorMessage = $errorMessage;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getJS($js) {
		return $js;
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