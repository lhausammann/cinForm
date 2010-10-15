<?php
require_once('../simpletest/autorun.php');
require_once('../../validator/regexValidator.php');

class RegexValidatorTestCase extends UnitTestCase {
	public function testRegexValidator() {
		$validator = new RegexValidator('%[0-9]*{4}%');
		$this->assert ( $validator->validate("hell"), 0);
		$this->assert ( $validator->validate("123"),0);
		$this->assert ($validator->validate("1234"),1);
		$this->assert($validator->validate("12345"),0);
	}


}