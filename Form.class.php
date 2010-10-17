<?php 
class Form {
	protected $fields = array();
	protected $errorMessage= "Errors occured";
	protected $name = 'form';
	protected $method = "post";
	protected $action = "";
	
	public function __construct($name = '', $action ='', $method='post') {
		$this->name = $name;
		$this->action = $action;
		$this->method = $method;
	}
	
	public function isSubmitted() {
		return isset($_REQUEST[$this->name]);
	}
	
	// save the successfully validated and transformed form:
	public function onSuccess($validatedAndTransformedForm) {
		
	}
	
	public function addField ($field) {
		$this->fields[] = $field;
		return $this;
	}
	
	public function validate() {
		$hasErrors = false;
		foreach ($this->fields as $field) {
			$fieldError = $field->validate();
			if ($fieldError==true && $hasErrors == false) {
				$hasErrors = true;
			}
		} return $hasErrors;
	}
	
	public function fillFromRequest() {
		foreach ($this->fields as $field) {
			$field->setValue($_REQUEST[$field->getName()]);
		}
	}
	
	// maion mehthod: fills and draws the whole form:
	public function render() {
		$html = "<form action='" . $this->action ."' method='" . $this->method . "'>";
		foreach ($this->fields as $field ) {
			$html.=$field->render();
		}
		// add submit button
		$html.="<input type='submit' name='".$this->name."' />";
		$html.="</form>";
		return $html;
	}
	
	public function toStorageFormat() {
		foreach($this->fields as $field) {
			$field->toStorageFormat();
		}
	}
	
	public function display() {
		if ($this->isSubmitted()) {
			$this->fillFromRequest();
			$this->validate();
			$this->toStorageFormat();
		} return $this->render();
	}
}

?>