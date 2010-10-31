<?php
require_once('/field/Submit.php');
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
		$this->fields[$field->getName()] = $field;
		return $this;
	}
	
	public function getField($name) {
		return $this->fields[$name];
	}

	public function validate() {
		$formValid = true;
		foreach ($this->fields as $field) {
			
			if ($field->validate()==false) {
				$formValid = false;
				// just collect the main error of the current field:
				$this->errors[$field->getName()] = $field->getError();
			}
		}
		/* 	at this point we have to transform the form to the storage format:
		 *  note that the render() method will display the form again correctly,
		 *  if it is displayed correctly.
		 */
		// TODO handle this in a field state, which is more transparent.
		$this->toStorageFormat();
		return $formValid;
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
		$submit = new SubmitField();
		$submit->setName($this->name);
		$html.= $submit->render();
		$html.="</form>";
		return $html;
	}

	public function toStorageFormat() {
		foreach($this->fields as $field) {
			$field->setValue($field->toStorageFormat());
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
			}
			// set the js field config
			$jsConfig["rules"][$field->getName()] = $validatorJs['rule'];
			$jsConfig['messages'][$field->getName()] = $validatorJs['message'];
		}

		// return the jQuery script:
		$script = '<script>';
		$script .='$(document).ready(function(){';
		$script .= '$("#'.$this->name.'").validate(';
		$json = json_encode($jsConfig);
		$script.=$json;
		$script .=")";
		$script.="})";
		$script.="</script>";
		return $script;
	}
}


// a cinergy form can be loaded by a config-x(ht)ml file.
class CinForm extends JSForm {
	private $attributes;
	private $processCinField = false;
	private $skipTags = array();
	private $processLevel = 0;
	
	protected $template = "../config/formConfig.xml";
	protected $currentCinField = null;
	
	protected $form = null;
	
	public function setConfig($path) {
		$this->template = $path;
	}
	
	public function render() {
		// first, we load all cinFields and construct the form:
		$dom = new DOMDocument();
		$dom->load($this->template);
		$xp = new DomXPath($dom);
		// get form attributes and cinFields by xpath:
		$formConfigs = $xp->query('//cinForm');
		
		// we cannot access linked domNodes with $formConfig = $formConfigs[0], so use break:
		foreach ($formConfigs as $formConfig) {
			break;
		}
			
		$form = new JSForm($formConfig->getAttribute('name'), $formConfig->getAttribute('action'), $formConfig->getAttribute('method'));
		// find all fields and add them to the form:
		$fieldConfigs = $xp->query('//cinField', $formConfig);
		foreach ($fieldConfigs as $fieldConfig) {
			// set up the field:
			$fileName = ucfirst($fieldConfig->getAttribute('type'));
			$className =  $fileName . 'Field';
			require_once('/field/' . $fileName . '.php');
			$name = $fieldConfig->getAttribute('name');
			$default = $fieldConfig->getAttribute('default');
			$label = $fieldConfig->getAttribute('label');
			$field = new $className($name, $label, $default);
			$validators = $fieldConfig->getElementsByTagName('validator');
			foreach ($validators as $validatorConfig) {
				$className = ucfirst($validatorConfig->getAttribute('name')) . "Validator";
				require_once('../validator/' . $className .".php");
				
				$validator = new $className();
				$validatorConfig->removeAttribute('name');
				// set up validator properly:
				foreach ($validatorConfig->attributes as $name => $value ) {
					// we assume there is a setter method for each 
					// property to access from the xml file:
					$call = "set" . ucfirst($name);
					// parse to string
					$v = (string) $value->value;
					$validator->$call($v);
				}
				$field->addValidator($validator);
			} 
			
			$form->addField($field);
		}
		
		// validate the form:
		if ($form->isSubmitted()) {
			$form->fillFromRequest();
			$form->validate();
		}
		
		$this->form = $form;

		// After constructing and validating the form, we process the config
		// file again, using sax this time: We just convert the cinForm/cinField tags,
		// and just outputting everything else:
		
		$xml = xml_parser_create( );
		xml_set_object($xml, $this);
		xml_set_element_handler($xml, 'open', 'close');
		xml_set_character_data_handler($xml, 'cdata');
		xml_parser_set_option($xml, XML_OPTION_CASE_FOLDING, false);
		
		// fetch all cinFields and construct object:
		
		$fp = fopen($this->template, 'r');
		$xmlStr = '';
		while ($data = fread($fp, 4096)) {
			$xmlStr .= $data;
		} fclose($fp);
		
	
		xml_parse($xml, $xmlStr, true);
		xml_parser_free($xml);
	}
	
	public function open($parser, $tag, $attributes) {
		$this->processLevel++;
		// skip the root, or fields which are not preocessable:
		if ($this->processLevel==1 || in_array($tag, $this->skipTags)) {	
			return;
		}
		
		// output all other tags 'as-is'
		// however, single tags (e.g. <hr />) will be displayed as <hr></hr>
		if ($tag=='cinForm') {
			echo "<form action='" . $attributes['action'] ."' method='" . $attributes['method'] . "' id='".$attributes['name']."'>";
		} else if ($tag != 'cinField' && ! $this->processCinField) {
			$attr = '';
			
			foreach ($attributes as $name => $value) {
				$attr.=" " . $name . "=" . '"' . $value. '"';
			}
			echo ($attr!='' ? "<" . $tag . " " . $attr . " >" : "<" . $tag . ">");
		} else if ($tag=='cinField') {
			$this->currentCinField = $attributes['name'];
			$this->processCinField = true;
		}
	}
	
	public function cdata($parser, $text) {
		echo $text;
	}
	
	public function close($parser, $tag) {
		$this->processLevel--;
		// skip root tag and not processing tags:
		if ($processLevel==1 || in_array($tag, $this->skipTags)) {
			return;
		}
		
		if ($tag=='cinForm') {
			$submit = new SubmitField($this->form->name);
			echo $submit->render();
			echo "</form>";
		} else if ($tag != 'cinField' && ! $this->processCinField) {
			echo "</" . $tag . ">";
		} else if ($tag=='cinField') {
			echo $this->form->getJQueryValidation();
			echo $this->form->getField($this->currentCinField)->render();
			$this->currentCinField = false;
			$this->processCinField = false;
		}
	}
}


?>