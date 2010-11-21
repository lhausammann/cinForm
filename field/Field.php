<?php
require_once( '../validator/RequiredValidator.php');
require_once( '../transformer/EntitiesTransformer.php');

class Field {
	const DISPLAY_FORMAT = 1;
	const STORAGE_FORMAT = 2;
	
	protected $type="text";
	protected $value='';
	protected $errors = array();
	protected $name = '';
	protected $label = '';
	protected $hasErrors = "";
	protected $cssClasses = array();
	
	protected function compileCss() {
		return " " . $this->name . " " . implode(" ", $this->cssClasses) . " ";
	}
	
	// contains information about the format of the field value:
	// after validating the form
	public $fieldState = self::DISPLAY_FORMAT;
	
	// wether or not the required validator should be added:
	protected $isOptional = false;
	
	protected $validators = array();
	protected $transformers = array();
	protected $defaultValue = '';
	

	
	public function __construct($name, $label='label', $value='', $isOptional = false) {
		$this->name = $name;
		$this->label = $label;
		// $this->value = $value;

		
		if ($isOptional==false) {
			$this->addValidator(new RequiredValidator('please enter a value'));
			$this->isOptional = false;
		}
		// the defaultg transformer escapes and unescapes html input
		$this->addTransformer(new EntitiesTransformer());
		$this->init();
		
		// after initing, we can set the default value properly:
		$this->setDefaultValue($value);
	}

	// initailize custom validators and transformers here
	public function init() {	
	}
	
	
	
	public function getName() {
		return $this->name;
	}
	
	public function setDefaultValue($value) {
		// first set the value, then toStorageFormat transforms it
		// to the correct format:
		$this->defaultValue = $value;
		$this->value = $value;
		$this->value = $this->toStorageFormat();
	}
	
	public function addValidator($validator) {
		$this->validators[$validator->getName()] = $validator;
		return $this;
	}
	
	public function removeValidator($name) {
		unset ($this->validators[$name]);
		return $this;
	}
	
	public function getValidators() {
		return $this->validators;
	}
	
	public function addTransformer($transformer) {
		$this->transformers[$transformer->getName()] = $transformer;
		return $this;
	}
	
	public function removeTransformer($name) {
		// unset ($this->transformers[$name]); does not work
		$tmp = array();
		// @todo: Use array_splice
		foreach ($this->transformers as $key => $value) {
			if ($key == $name) {
				continue;
			} $tmp[$key] = $value;
		}
		$this->transformers = $tmp;
		return $this;
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
			$value = $t->displayFormat($value, $this);
		} return $value;
	}
	
	public function toStorageFormat() { 
		$value = $this->value;
		if (count($this->transformers)==0) {
			return $this->value;
		}
		
		foreach ($this->transformers as $t) {
			$value = $t->storageFormat($value, $this);
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
		return "<input type='hidden' name='".$this->name . "' class='".$this->name."' value='" . $this->getValue() . "' />";
	}
	
	// override this function to create a custom label (or non label at all):
	protected function labelHtml() {
		return "<label  for='". $this->name . "'>" . $this->label . "</label>";
	}
	
	/** get the html for a field including its attributes:
	 * just return the rendered field and its value
	 */
	public function toHtml() {
		$return .= "<input type='" . $this->type . "' name='" . $this->name . "' id='".$this->name . "' class='" . $this->compileCss() . "' value='" . $this->toDisplayFormat($this->getValue()) . "' />";
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