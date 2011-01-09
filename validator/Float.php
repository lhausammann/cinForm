<?php
require_once('RegexValidator.php');


class Validator_Float extends Validator_Regex {
	protected $name = 'float';
	protected $errorMessage = "not a float number";
	
	public function __construct($regex = '') {
		parent::__construct('/^(-)?([0-9]*)(\.[0-9]+)?$/');
	}
}