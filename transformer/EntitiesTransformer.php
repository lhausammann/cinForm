<?php
// protects the displayed form from html:
class EntitiesTransformer {
	public function displayFormat($data, $field) {
		return htmlentities($data, ENT_QUOTES);
	}
	
	public function storageFormat($data, $field) {
		return html_entity_decode($data, ENT_QUOTES);
	}
}