<?php 
class Form {
	protected $fields = array();
	protected $errorMessage= "Errors occured";
	protected $name = 'form';
	protected $method = "post";
	protected $action = "";
	
	protected $errors = array();
	
	public function __construct($name = '', $action ='', $method='post') {
		$this->name = $name;
		$this->action = $action;
		$this->method = $method;
	}
	
	public function hasErrors() {
		return  count($this->errors)> 0 ? true : false; 
	}
	
	public function isSubmitted() {
		return isset($_REQUEST[$this->name]);
	}
	
	public function addField ($field) {
		$this->fields[] = $field;
		return $this;
	}
	
	public function validate() {
		$formValid = true;
		foreach ($this->fields as $field) {
			$fieldValid = $field->validate();
			if ($fieldValid==false) { 
				if ($formValid == true) {
					$formValid = false;
				}
				// just collect the main error of the current field:
				$this->errors[$field->getName()] = $field->getError();
			}
		} 
		if ($formValid) {
			return true;
		} else {
			return false;
		}
	}
	
	public function fillFromRequest() {
		foreach ($this->fields as $field) {
			$field->setValue($_REQUEST[$field->getName()]);
		}
	}
	
	public function renderErrors() {
		$html="";
		$html.="<span class='caption'>Folgende Felder prüfen:</span>";
		$html.="<ul class='error'>";
		foreach ($this->errors as $fieldName => $error) {
			$html.="<li><a href='#".$fieldName."'>". "<strong>" . $fieldName . "</strong>: " . $error . "</a></li>";
		}
		$html.="</ul>";
		return $html;
	}
	
	// maion mehthod: fills and draws the whole form:
	public function render() {
		$html = '';
		if ($this->hasErrors()) {
			$html.= $this->renderErrors();
		}
		
		$html .= "<form action='" . $this->action ."' method='" . $this->method . "' id='".$this->name."'>";
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
}

// this form depends on the jQuery-Validation plugin:
// transform validator fields and messages to jQuery validation format.

class JSForm extends Form {
	public function getJQueryValidation() {
		$json = '{';
		$jsMessages = '';
		$jsRules = '';
		$jsConfig = array( "rules" => "", "messages" => array());
		
		foreach ($this->fields as $field) {
			$validatorJs = array();
			$validators = $field->getValidators();
			foreach ($validators as $validator) {
				$validatorJs = $validator->getJS($validatorJs);
				
				// $validatorJS['rule'];
				// $jsConfig["messages"][$field->getName()] = $validatorJS['message'];
				var_dump($jsConfig['messages']);
			} 	
			// set the js field config
			$jsConfig["rules"][$field->getName()] = $validatorJs['rule'];
			$jsConfig['messages'][$field->getName()] = $validatorJs['message'];
		} 
		
		// return the jQuery script:
		$script = '<script>';
		$script .='$(document).ready(function(){';
			$script .= '$("#'.$this->name.'").validate(';
				//$script .=	'{rules: {' . $jsRules. '}';//,messages:{'.$jsMessages.'}});';
				$json = json_encode($jsConfig);
				// $json = str_replace('"', '', $json);
				$script.=$json;
			$script .=")";
		$script.="})";
		$script.="</script>";
		
		//	$script .= '})});</script>';
		return $script;
	} 
	
}
?>