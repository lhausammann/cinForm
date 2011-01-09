<?php
/** autoload stuff */
if (ENABLE_AUTOLOAD) {
	function __autoload($name) {
		$filepath = str_replace ('_', '/', $name);
		echo $filepath;
		require_once($filepath . '.php');
	}
}



class Form {
	protected $fields = array();
	protected $errorMessage= "Errors occured";
	protected $name = 'form';
	protected $method = "post";
	protected $action = "";

	protected $renderer = null;

	protected $errors = array();

	public function __construct($name = '', $action ='', $method='post') {
		$this->name = $name;
		$this->action = $action;
		$this->method = $method;
	}

	public function setRenderer($renderer) {
		// set the renderer to use in add field...
		$this->renderer = $renderer;
		// and overwrite existing renderer on fields:
		foreach ($this->fields as $field) {
			$field->setRenderer($renderer);
		}
	}



	public function setName($name) {
		$this->name = $name;
	}

	public function setMethod($method) {
		$this->method = $method;
	}

	public function setAction ($action) {
		$this->action = $action;
	}

	public function startForm() {
		return "<form action='" . $this->action ."' method='" . $this->method . "' id='".$this->name."'>";
	}

	public function endForm() {
		return "</form>";
	}

	public function hasErrors() {
		return  count($this->errors)> 0 ? true : false;
	}

	public function isSubmitted() {
		return isset($_REQUEST[$this->name]);
	}

	public function addField ($field) {
		$this->fields[$field->getName()] = $field;
		$field->setRenderer($this->renderer);
		return $this;
	}

	public function getFields() {
		return $this->fields;
	}

	public function getField($name) {
		return $this->fields[$name];
	}

	public function getName() {
		return $this->name;
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
		 *  if it contains errors.
		 */
		// TODO is there a simpler solution around ???
		$this->toStorageFormat();
		return $formValid;
	}

	public function fillFromRequest() {
		foreach ($this->fields as $field) {
			// TODO for composite fields:
			$field->fillFromRequest();
			// $field->setValue($_REQUEST[$field->getName()]);
		}
	}

	public function hide($additionalHtml) {
		$html .= "<form action='" . $this->action ."' method='" . $this->method . "' id='".$this->name."'>";
		foreach ($this->fields as $field) {
			$html.=$field->hide();
		}
		return $html . $additionalHtml . "</form>";
	}

	public function renderErrors() {
		if ($this->hasErrors()) {
			$html="";
			$html.="<span class='caption'>Folgende Felder prüfen:</span>";
			$html.="<ul class='error'>";
			foreach ($this->errors as $fieldName => $error) {
				$html.="<li><a href='#".$fieldName."'>". "<strong>" . $fieldName . "</strong>: " . $error . "</a></li>";
			}
			$html.="</ul>";
			return $html;
		} else {
			return '';
		}
	}

	// maion mehthod: fills and draws the whole form:
	public function render() {
		if ($this->hasErrors()) {
			$html.= $this->renderErrors();
		}

		$html .= $this->startForm();
		foreach ($this->fields as $field ) {
			$html.=$field->render();
		}
		// add submit button
		$submit = new Field_Submit(null);
		$submit->setName($this->name);
		$html.= $submit->render();
		$html.=$this->endForm();
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
		$jsConfig = array( "errorElement" => "span", "rules" => "", "messages" => array());

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

	public function startForm() {
		$start = $this->getJQueryValidation() . parent::startForm();
		return $start;
	}
}


// injects a form in the parsed html code.
// Because the form class is unknown at compile-time, we
// cannot subclass it. Therefore we redirect all method calls
// to the target form. Only the render() method will
// call the parser again, to ouptut the form in the respective html document.
// ParserForm should never be instantiated directly: Retrieve it by $parser->getForm()
class ParserForm {

	private $targetForm;
	private $parser;

	public function __construct($form, $parser) {
		$this->targetForm = $form;
		$this->parser = $parser;
	}

	// delegate function call to the target object:
	public function __call($functionName, $args) {
		return call_user_func_array(array($this->targetForm, $functionName), $args);
	}

	// overwrite all existing renderers in the form:
	public function setRenderer($renderer) {
		foreach ($this->targetForm->getFields() as $field ) {
			$field->setRenderer($renderer);
		}

	}

	public function render() {
		if ($this->parser) {
			return $this->parser->render();
		} else {
			return $this->targetForm->render();
		}
	}
}


// a cinergy form can be loaded by a config-x(ht)ml file.

class CinForm  {
	private $attributes;
	private $processCinField = false;
	private $skipTags = array();
	private $processLevel = 0;
	private $renderOutput = "";
	protected $template = "../config/formConfig.xml";
	protected $currentCinField = null;


	protected $specialClassNames = array(
		'text' => array('class' => 'TextClearDefault', 'file' => 'TextClearDefault.php'),
		'textarea' => array('class' => 'Textarea', 'file' => 'TextArea.php' ),
		'select' => array('class' => 'Select', 'file' => 'FieldGroup.php'),
		'radio' => array('class' => 'Radio', 'file' => 'FieldGroup.php')
	);

	protected $form = null;

	public function __construct($path='') {
		$this->setConfig($path);
		$this->init();
	}

	public function setConfig($path) {
		$this->template = $path;
	}

	public function init() {
		// first, we load all cinFields and construct the form:
		$dom = new DOMDocument();
		$dom->load($this->template);
		$xp = new DomXPath($dom);
		// get form attributes and cinFields by xpath:
		$formConfigs = $xp->query('//cinForm');

		// we cannot access linked domNodes with $formConfig = $formConfigs[0], so use break:
		// (there is only one form tag per document)
		foreach ($formConfigs as $formConfig) {
			break;
		}

		$className = $formConfig->getAttribute('type');
		if (! $className) {
			$className = 'Form';
		}
		echo $className;
		$this->form = new $className;
		$this->form->setName($formConfig->getAttribute('name'));
		$this->form->setAction($formConfig->getAttribute('action'));
		$this->form->setMethod($formConfig->getAttribute('method'));

		// find
		// foreach ($formConfig->getElementsByTagName('renderer'))

		// find all fields and add them to the form:
		$fieldConfigs = $xp->query('//cinField', $formConfig);
		foreach ($fieldConfigs as $fieldConfig) {
			// set up the field:
			$typeName = $fieldConfig->getAttribute('type');
			$fieldConfig->removeAttribute('type');

			if (!array_key_exists($typeName, $this->specialClassNames)) {
				$className =  ucfirst($typeName) . 'Field';
				$fileName = $typeName . '.php';
			} else {
				$className = $this->specialClassNames[$typeName]['class'];
				$fileName = $this->specialClassNames[$typeName]['file'];
			}
				
				
				
			require_once('/field/' . $fileName);
			$name = $fieldConfig->getAttribute('name');
			$default = $fieldConfig->getAttribute('default');
			$label = $fieldConfig->getAttribute('label');
			$field = new $className($name, $label, $default);

			// remove default attributes before processing other attributes
			foreach (array('name','default','label') as $name){
				$fieldConfig->removeAttribute($name);
			}

			// calls the setter method for each attribute:
			foreach ($fieldConfig->attributes as $name => $value) {
				$call = 'set' . ucfirst($name);
				$field->$call($value->value);
			}

			// process validators:
			$validators = $fieldConfig->getElementsByTagName('validator');
			foreach ($validators as $validatorConfig) {
				$className = ucfirst($validatorConfig->getAttribute('name')) . "Validator";
				require_once('../validator/' . $className .".php");

				$validator = new $className();
				$validatorConfig->removeAttribute('name');
				// set up validator properly:
				foreach ($validatorConfig->attributes as $name => $value ) {
					$call = "set" . ucfirst($name);
					// parse to string
					$v = (string) $value->value;
					$validator->$call($v);
				}
				$field->addValidator($validator);
			}

			$options = $fieldConfig->getElementsByTagName('option');
			$opts = array();
			foreach ($options as $option) {
				$opts[$option->getAttribute('name')] = $option->getAttribute('value');
			}

			if (count($opts)) {
				$field->setOptions($opts);
			}

			$this->form->addField($field);
		}
	}


	public function getForm() {
		// bind the form to the parser
		return new ParserForm($this->form, $this);
	}

	public function render() {
		// reset render output
		$this->renderOutput = '';
		$this->parse();
		return $this->renderOutput;
	}


	public function parse() {
		$xml = xml_parser_create( );
		xml_set_object($xml, $this);
		xml_set_element_handler($xml, 'open', 'close');
		xml_set_character_data_handler($xml, 'cdata');
		xml_parser_set_option($xml, XML_OPTION_CASE_FOLDING, false);

		$fp = fopen($this->template, 'r');
		$xmlStr = '';
		while ($data = fread($fp, 4096)) {
			$xmlStr .= $data;
		} fclose($fp);

		xml_parse($xml, $xmlStr, true);
		xml_parser_free($xml);
		return $this;
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
			$this->renderOutput .= $this->form->startForm();

		} else if ($tag=='cinField') {
			$this->currentCinField = $attributes['name'];
			$this->processCinField = true;
		} else if ($tag=='cinErrors') {
			$this->renderOutput .= $this->form->renderErrors();
			// 1. do not close and dont show this tag again:
			$this->skipTags[] = 'cinErrors';
		} else if ($tag != 'cinField' && $tag!='cinErrors' && ! $this->processCinField) {
			$attr = '';

			foreach ($attributes as $name => $value) {
				$attr.=" " . $name . "=" . '"' . $value. '"';
			}
			$this->renderOutput .= ($attr!='' ? "<" . $tag . " " . $attr . " >" : "<" . $tag . ">");
		}
	}

	public function cdata($parser, $text) {
		$this->renderOutput .= $text;
	}

	public function close($parser, $tag) {
		// skip root tag and non processing tags:
		if ($this->processLevel==1 || in_array($tag, $this->skipTags)) {
			return;
		}

		if ($tag=='cinForm') {
			$submit = new SubmitField($this->form->getName());
			$this->renderOutput .= $submit->render();
			$this->renderOutput .= "</form>";
		} else if ($tag != 'cinField' && ! $this->processCinField) {
			$this->renderOutput .= "</" . $tag . ">";
		} else if ($tag=='cinField') {
			$this->renderOutput .= $this->form->getField($this->currentCinField)->render();
			$this->currentCinField = false;
			$this->processCinField = false;
		}
		$this->processLevel--;
	}

}


?>