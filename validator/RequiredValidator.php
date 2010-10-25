<?php 
// base
require_once('Validator.php');

class RequiredValidator extends Validator {
	protected $errorMessage = "cannot be empty";
	
	

	 
	public function getJS($js) {
		
		$js["message"]["required"] = $this->getErrorMessage();
		$js["rule"]["required"] = true;
		return $js;
	}
	
	
	public function validate($value) {
		if ( ($value==='') || ($value===null)) {
			return false;
		} 
		return true;
	}
}