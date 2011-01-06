<?php
require_once( '../validator/RequiredValidator.php');
require_once( '../transformer/EntitiesTransformer.php');
require_once( '../renderer/ClearDefaultRenderer.php' );
require_once( '../renderer/RedBorderRenderer.php' );
require_once( '../renderer/RedBorderRenderer.php' );

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
	protected $defaultValue;
	// contains information about the format of the field value:
	// after validating the form
	
	public $fieldState = self::DISPLAY_FORMAT;
	
	// wether or not the required validator should be added:
	protected $isOptional = false;
	
	protected $validators = array();
	protected $transformers = array();
	
	
	
	public function getType() {
		return $this->type;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function getErrors() {
		return $this->errors;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getLabel() {
		return $this->label;
	}
	
	public function getDefaultValue() {
		return $this->defaultValue;
		
	}
	
	public function compileCss() {
		return " " . $this->name . " " . implode(" ", $this->cssClasses) . " ";
	}
	
	

	
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
	
	public function setRenderer ($renderer) {
		$this->renderer = $renderer;
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
	
	public function hasErrors() {
		return (count ($this->errors) > 0 ? true : false);
	}

	// gets the first error
	public function getError() {
		return $this->errors[0];
	}
	
	/**
	 * @param $className the css class name
	 */
	public function hasClass($className) {
		return in_array($className, $this->cssClasses);
		
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
	public function labelHtml() {
		return "<label  for='". $this->name . "'>" . $this->label . "</label>";
	}
	
	public function defaultHtml() {
		$return .= "<input type='" . $this->getType() . "' name='" . $this->getName() . "' id='".$this->getName() . "' class='" . $this->compileCss() . "' value='" . $this->toDisplayFormat($this->getValue()) . "' />";
		return $return;
	}
	
	public final function toHtml() {
		return $this->defaultHtml();
	}
	
	public function addClass($className) {
		$this->cssClasses[] = $className;
	}
	
	
	public function render() {
		if ($this->renderer) {
			$html = $this->renderer->renderElement ($this);
		} else {
			$html = $this->toHtml();
		}
		return $html;	
	}
}




?>