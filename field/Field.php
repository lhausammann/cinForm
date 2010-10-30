<?php
require_once( '../validator/RequiredValidator.php');
require_once( '../transformer/EntitiesTransformer.php');

class Field {
	protected $type="text";
	protected $value='';
	protected $errors = array();
	protected $name = '';
	protected $label = '';
	protected $hasErrors = "";
	
	// this will not be validated by a normal validator
	protected $isOptional = false;
	
	protected $validators = array();
	protected $transformers = array();
	

	public function __construct($name='text', $label='Enter text', $value='', $isOptional = false) {
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		if ($isOptional==false) {
			$this->addValidator(new RequiredValidator());
			$this->isOptional = false;
		}
		$this->addTransformer(new EntitiesTransformer());
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
	
	public function getValidators() {
		return $this->validators;
	}
	
	public function addTransformer($transformer) {
		$this->transformers[] = $transformer;
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
		return $this->value;
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
	
	public function toDisplayFormat() {
		$value = $this->value;
		if (count($this->transformers)==0) {
			return $this->value;
		} foreach ($this->transformers as $t) {
			$value = $t->displayFormat($value);
		} return $value;
	}
	
	public function toStorageFormat() { 
		$value = $this->value;
		if (count($this->transformers)==0) {
			return $this->value;
		} foreach ($this->transformers as $t) {
			echo "transforming " . $this->value . " to: ";
			echo $t->storageFormat($this->value);
			$value = $t->storageFormat($value);
		} 
		return $value;
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
	
	// override this function to create a custom label (or non label at all):
	protected function labelHtml() {
		return "<label  for='". $this->name . "'>" . $this->label . "</label>";
	}
	
	/** get the html for a field including its attributes:
	 * just return the rendered field and its value
	 */
	public function toHtml() {
		$return .= "<input type='" . $this->type . "' name='" . $this->name . "' id='".$this->name . "' class='".$this->name."' value='" . $this->toDisplayFormat($this->getValue()) . "' />";
		return $return;
	}
	
	/*
	 * renders the whole field, including surrounding validator js config asf.
	 * Only override if really necessary - use toHtml() instead.
	 */
	public function render() {
		$hasErrorCss = $this->hasErrors() ? " error " : "";
		$return = "<div class='wrapper " . $this->name . $hasErrorCss . "'>";
		$return .= $this->labelHtml();
		$return .="<a name='". $this->name."'></a>";
		$return.=$this->toHtml();
		
		if ($this->hasErrors()) {
			$return .= "<span class='error'>" . $this->getError() . "</span>";
		}
		
		$return .= "</div>";
		return $return;
	}
}




?>