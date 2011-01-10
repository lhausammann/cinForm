<?php 
class Field_Composite extends Field_Base {
	protected $type="composite";
	protected $fields = array();
	
	/* new method for composites: addField */
	public function addField($field) {
		$this->fields[$field->getName()] = $field; 
	}
	
	/* override existing methods: */
	public function hide() {
		foreach ($this->fields as $field) {
			$field->hide();
		}
	}
	
	public function setElementRenderer($renderer) {
		foreach ($this->fields as $field) {
			$field->setRenderer($renderer);
		}
	}
	
	public function validate() {
		foreach ($this->fields as $element) {
			if ($element->validate() == false) {
				$this->errors = array_merge($element->errors);
			}
		} return  count($this->errors) ? false : true;
	}
	
	public function fillFromRequest() {
		foreach ($this->fields as $field) {
			$field->fillFromRequest();
		}
	}
	
	public function defaultHtml() {
		$html = "<div class='composite'>";
		foreach ($this->fields as $field) {
			$html .= $field->render();	
		}
		$html.="</div>";
		return $html;
	} 
	
	/* not possible to implement on composites: does nothing */
	public function setDefaultValue() {
		//throw new Exception ('Operation setDefaultValue() not implemented on composites.');
	}
	
}

?>