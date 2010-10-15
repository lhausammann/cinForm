<?php
require_once('../simpletest/autorun.php');
require_once('../../validator/NumericValidator.php');

class IntTestCase extends UnitTestCase {
	public function testIntValidator() {
		$intValidator = new IntValidator();
		$this->assertTrue($intValidator->validate('-12345'));

		$this->assertTrue($intValidator->validate(123));
		$this->assertTrue($intValidator->validate(0));
		// + not allowed as prefix
		$this->assertFalse($intValidator->validate('+15'));
		$this->assertFalse($intValidator->validate(1.23));
		$this->assertFalse($intValidator->validate('-123a'));
		$this->assertFalse($intValidator->validate('123-25'));
		$this->assertFalse($intValidator->validate('123+25'));
	}
	
	public function testFloatValidator() {
		$fv = new FloatValidator();
		// plus not allowed as prefix:
		$this->assertFalse($fv->validate('+3.4'));
		$this->assertTrue($fv->validate(3));
		$this->assertTrue($fv->validate(-3));
		$this->assertTrue($fv->validate(-3.5));
		$this->assertFalse($fv->validate('-3.3.3'));
		$this->assertFalse($fv->validate('+3.3.3'));
		
	}

}