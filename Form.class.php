<?php 
class Form {
	public function render() {
		
		$fields = $this->getFields();
		foreach ($fields as $field) {
			
			
		}
		
	}
	
	public function validate() {
		$fields = $this->getFields();
		foreach ($fields as $field) {
			if (! $field->validate()) {
				$this->
				
			}
			
		}
		
		
	}
	
	
	public function serialize() {
		
		
	}
	
	
	$form = new Form();
	$this->addField("name", "text");
	$this->
	
}


?>