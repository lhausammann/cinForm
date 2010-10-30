<?php
require_once('Field.php');
class TextArea extends Field {
public function toHtml() {
		$html = "<textarea id='".$this->name."' name='".$this->name . "' class='".$this->name."' rows='5' cols='35'>";
		$html .= $this->toDisplayFormat($this->getValue());
		$html .= "</textarea>";
		return $html;
	}
}