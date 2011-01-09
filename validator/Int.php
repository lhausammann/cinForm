<?php
class Validator_Int extends Validator_Regex {
	protected $name = 'int';
	protected $errorMessage = "not an integer";
	public function __construct($regex = '') {
		parent::__construct('/^(-)?([0-9]*)$/');
	}
}