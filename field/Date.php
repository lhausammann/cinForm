<?php
require_once('Field.php');
require_once('../validator/RegexValidator.php');
require_once('../transformer/DateTransformer.php');

class DateField extends Field {
	public function init() {
		$this->transformers[] = new DateTransformer();
		$this->validators[] = new RegexValidator("/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/", "Date has to be in the format: dd.mm.yyyy");
	}
}