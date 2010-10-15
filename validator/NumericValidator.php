<?php
require_once('RegexValidator.php');

class IntValidator extends RegexValidator {
	public function __construct($regex = '') {
		parent::__construct('/^(-)?([0-9]*)$/');
	}
}

class FloatValidator extends RegexValidator {
	public function __construct($regex = '') {
		parent::__construct('/^(-)?([0-9]*)(\.[0-9]+)?$/');
	}
}