<?php
require_once('../simpletest/autorun.php');
require_once('../../validator/regexValidator.php');

class RegexValidatorTestCase extends UnitTestCase {
	public function testRegexValidator() {
		$validator = new RegexValidator('/^[0-9]{4}$/');
		$this->assertFalse ( $validator->validate("hell"));
		$this->assertFalse( $validator->validate("123"));
		$this->assertTrue ($validator->validate("1234"));
		$this->assertFalse($validator->validate("1234567"));
	}
}