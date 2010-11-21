<?php
require_once('RegexValidator.php');

class IntValidator extends RegexValidator {
	protected $name = 'int';
	protected $errorMessage = "not an integer";
	public function __construct($regex = '') {
		parent::__construct('/^(-)?([0-9]*)$/');
	}
}

class FloatValidator extends RegexValidator {
	protected $name = 'float';
	protected $errorMessage = "not a float number";
	
	public function __construct($regex = '') {
		parent::__construct('/^(-)?([0-9]*)(\.[0-9]+)?$/');
	}
}