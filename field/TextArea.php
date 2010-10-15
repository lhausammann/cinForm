<?php
require_once('Field.php');
class TextArea extends Field {
public function render() {
		$return = "<div class='wrapper " . $this->name . "'>";
		$return .= "<label  for=". $this->name . ">" . $this->label . "</label>";
		$return .= "<textarea id=".$this->name . "' class='".$this->name."'>";
		$return .= $this->value;
		$return .= "</textarea>";
		if ($this->hasErrors()) {
			$return .= "<span class='error'>" . $this->getError() . "</span>";
		}
		
		$return .= "</div>";
		return $return;
	}
}