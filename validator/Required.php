<?php 

class Validator_Required extends Validator_Base {
	protected $name='required';
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