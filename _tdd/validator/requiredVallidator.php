<?php 
require_once('../simpletest/autorun.php');
require_once('../../validator/RequiredValidator.php');

class ValidatorTestCase extends UnitTestCase {
    function FieldTestCase() {
        $this->UnitTestCase('Field test');
    }
    
    function setUp() {
    }
    
    function tearDown() {
    }
    
    function testConstructValidatorWithoutMessageGetsNoErrorMessage() {
    	$validator = new RequiredValidator();
    	$this->assertEqual('', $validator->getErrorMessage());
    }
    
    function testConstructValidatorWithMessageGetsErrorMessage() {
    	$validator = new RequiredValidator('cannot be empty');
    	$this->assertEqual('cannot be empty', $validator->getErrorMessage());
    }
    
    function testRequiredValidation() {
     	$validator = new RequiredValidator();
     	
 		$this->assertFalse($validator->validate('')); 
 		$this->assertTrue( $validator->validate('0'));
 		$this->assertTrue($validator->validate('a text'));
    }
}


?>