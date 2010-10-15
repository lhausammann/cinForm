<?php
require_once('../simpletest/autorun.php');
require_once('../../validator/lengthValidator.php');

class LengthValidatorTestCase extends UnitTestCase {
	public function testLengthValidator() {
		$validator = new LengthValidator(5,11);
		$this->assertFalse ( $validator->validate("hell"));
		$this->assertTrue ( $validator->validate("hello"));
		$this->assertTrue ($validator->validate("hello world"));
		$this->assertFalse($validator->validate("hello wolrd!"));
	}

	public function testCorrectMessage() {
		$valid = new LengthValidator(0,5);
		
		$valid = new LengthValidator(0,5);
		$valid->validate('123456');
		$this->assertEqual( $valid->getErrorMessage(), 'At least 5 characters', "msg was: " . $valid->getErrorMessage());
		
		$valid2 = new LengthValidator(1,6);
		$this->assertEqual($valid2->getErrorMessage(), 'Between 1 and 6 characters');
		
		
	}
}