<?php 


class Option {
	public $name='';
	public $value='';
	public $type='select';
	
	// used for ids for radio buttons.
	public static $nr = 0;
	
	public $label = '';
	
	public $selected = false;
	
	
	public function __construct($name, $value, $type='select', $labelForRadio='') {
		$this->name=$name;
		$this->value = $value;
		$this->label = $labelForRadio;
		$this->type=$type;
		$this->nr = self::$nr++;
		
	}
	
	public function render() {
		if ($this->type!='select') { // radio
			if ($this->selected) {
				$selected = ' checked="checked" ';
			}
			if ($this->label) {
				$label = "<label for='" . $this->name . $this->nr . "' class='radio'>".$this->label."</label>";
			} else {
				$label = '';
			}
			
			return $label . '<input type="radio" class="radio" id="'.$this->name.$this->nr.'" name="' . $this->name. '" value="'.$this->value . '"' . $selected . '/><br />';
		}
		// select field
		$html = '<option value="'.$this->value. '"';
		if ($this->selected) {
			$html.= ' selected="selected"';
		}
		$html.='>' . $this->name;

		$html.="</option>";
		return $html;
		
	}
}

class Field_FieldGroup extends Field_Base {
	protected $type="multi";
	public function __construct($name, $label='', $values = array()) {
		parent::__construct($name, $label);
		if ($values) {
			$this->setOptions($values); 
		}
	}
	
	public function setOptions($values) {
		foreach ($values as $key => $value) {
			$this->options[] = new Option($key, $value);
		}
	}
	
	
public function defaultHtml() {
		$return .= "<select name='".$this->name .  "' id='".$this->name."' class='".$this->name."'>";
		foreach ($this->options as $option) {
			if ($option->value==$this->value) {
				$option->selected = true;
			}
			$return.=$option->render();
		}
		
		$return .= "</select>";
		return $return;
	}
}

// alias
class Field_Select extends Field_FieldGroup {}

class Field_Radio extends Field_FieldGroup {
public function __construct($name, $label='', $options = null) {
	parent::__construct($name, $label);
	$this->name = $name;
	
	if (count($options) > 0) {
		$this->setOptions($options);
	}
}	

public function setOptions($options) {
	if (count($options))
foreach ($options as $legend => $value) {
		$this->options[] = new Option($this->name, $value, 'radio', $legend);
	}
}
//  do not display a label on a radio group
// (every button has its own label:
public function labelHtml() {}

public function defaultHtml() {
		foreach ($this->options as $option) {
			if ($option->value==$this->value) {
				$option->selected = true;
			}
			$return.=$option->render();
		}
		return $return;
	}
}

