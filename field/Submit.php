<?php
// Please note, that the submit button has to have the name
// of the form

class Field_Submit extends Field_Base {
	public $type="submit";
	public function defaultHtml() {
		$html = "<input type='submit' name='" . $this->name . "' />";
		return $html;
	}
}