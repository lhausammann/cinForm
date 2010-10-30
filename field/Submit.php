<?php
// Please note, that the submit button has to have the name
// of the form
require_once('Field.php');
class SubmitField extends Field {
	public function toHtml() {
		$html = "<input type='submit' name='" . $this->name . "' />";
		return $html;
	}
	public function render() {
		return $this->toHtml();
	}
}