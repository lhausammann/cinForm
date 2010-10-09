<?php 
// base
require_once('Validator.php');

class RequiredValidator extends Validator {
	
	public function validate($value) {
		if ( ($value==='') || ($value===null)) {
			return false;
		} 
		echo "passed";
		return true;
	}
}