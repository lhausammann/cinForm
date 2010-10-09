<?php


class Field {
	protected $type="text";
	
	protected $value='';
	protected $errors = array();
	protected $name = '';
	protected $label = '';
	protected $hasErrors = "";
	protected $validators = array();

	public function __construct() {

	}

	public function addValidator($validator) {
		$this->validators[] = $validator;
		
		var_dump($this->validators);
		return this;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setLabel($label) {
		$this->label = $label;
	}

	public function setValue($value) {
		$this->value = $value;

	}

	public function hasErrors() {
		var_dump ( $this->errors);
		return (count ($this->errors) > 0 ? true : false);
	}

	public function getErrors() {
		return ($this->errors);
	}

	public function validate() {
		if (count($this->validators)) {
			foreach ($this->validators as $validator) {
				echo "value: " . $this->value;
				if ($validator->validate($this->value)==false) {
					echo "error msg is: " + $validator->getErrorMessage();
					$this->errors[] = $validator->getErrorMessage();
				}
			}
			return ! $this->hasErrors();
		// no validator set (yet): Evaluate to true:
		} else {
			return true;
		} 
		
	}


	public function render() {
		$return = "<div class='wrapper " . $this->name . "'>";
		$return .= "<label  for=". $this->name . ">" . $this->label . "</label>";
		$return .= "<input type='" . $this->type . "' id=".$this->name . "' class='".$this->name."' value='" . $this->value . "' />";
		if ($this->hasErrors()) {
			foreach ($this->errors as $error) {
				$return .= "<span class='error'>" . $error . "</span>";
			}
		}
		$return .= "</div>";
		return $return;
	}
}




?>