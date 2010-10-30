<?php
// protects the displayed form from html:
class EntitiesTransformer {
	public function displayFormat($data) {
		return htmlentities($data, ENT_QUOTES);
	}
	
	public function storageFormat($data) {
		return html_entity_decode($data, ENT_QUOTES);
	}
}