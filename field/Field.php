<?php


class Field {
	protected $type="text";
	protected $value='';
	protected $errors = array();
	protected $name = '';
	protected $label = '';
	protected $hasErrors = "";
	protected $validators = array();
	protected $transformers = array();
	
	
	protected $isStorageFormat = false;

	public function __construct($name='text', $label='Enter text', $value='') {
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		$this->init();
	}

	// initailize custom validators and transformers here
	public function init() {	
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function addValidator($validator) {
		$this->validators[] = $validator;
		return this;
	}
	
	public function addTransformer($transformer) {
		$this->transformers[] = $transformer();
		return this;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setLabel($label) {
		$this->label = $label;
	}

	public function setValue($value) {
		echo "setze value: " . $value;
		$this->value = $value;
	}
	
	public function getValue() {
		if (($this->isStorageFormat==false) || (! $this->transformers)) {
			return $this->value;
		} else {
			// put it through the formatter:
			foreach ($this->transformers as $t) {
				$this->value = $t->displayFormat($this->value);
			}
			return $this->value;
		}
		
	}

	public function hasErrors() {
		return (count ($this->errors) > 0 ? true : false);
	}

	// gets the first error
	public function getError() {
		return $this->errors[0];
	}
	
	// get all errors
	public function getErrors() {
		return ($this->errors);
	}
	
	public function toStorageFormat() { 
		echo "storageformat";
		if ($this->isStorageFormat==true) {
			return;
		}
		$this->isStorageFormat = true;
		if (count($this->transformers)==0) {
			return;
		} foreach ($this->transformers as $t) {
			echo "transforming " . $this->value . " to: ";
			echo $t->storageFormat($this->value);
			$this->value = $t->storageFormat($this->value);
		}
	}
	
	public function validate() {
		if (count($this->validators)) {
			foreach ($this->validators as $validator) {
				if ($validator->validate($this->value)==false) {
					$this->errors[] = $validator->getErrorMessage();
				}
			}
			return (! $this->hasErrors());
		// no validator set (yet): Evaluate to true:
		} else {
			return true;
		} 
	}
	
	// transforms the value to its display format (e.g. a timestamp has
	// to be displayed as a date):
	
	public function hide() {
		return "<input type='hidden' name=".$this->name . "' class='".$this->name."' value='" . $this->getValue() . "' />";
	}

	public function render() {
		$return = "<div class='wrapper " . $this->name . "'>";
		$return .= "<label  for=". $this->name . ">" . $this->label . "</label>";
		$return .= "<input type='" . $this->type . "' name='" . $this->name . "' id=".$this->name . "' class='".$this->name."' value='" . $this->getValue() . "' />";
		if ($this->hasErrors()) {
			$return .= "<span class='error'>" . $this->getError() . "</span>";
		}
		$return .= "</div>";
		return $return;
	}
}




?>