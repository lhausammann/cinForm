<?php 

require_once('../simpletest/autorun.php');
require_once('../../field/Field.php');
require_once('../../validator/RequiredValidator.php');

class FieldTestCase extends UnitTestCase {
    function FieldTestCase() {
        $this->UnitTestCase('Field test');
    }
    
    function setUp() {
    }
    
    function tearDown() {
    }
    
    function testFieldValidatesWithoutValidatorsSet() {
		$field = new Field();
		
		$field->setValue("fieldvalue");
		
		$field->setLabel("Field 1");
		$field->setName("field");
		
		$field->validate();
		$html = $field->render();
		echo $html;
		
		$this->assertEqual($field->getErrors(), array());
    }
    
    function testRequiredField() {
    	$field = new Field();
    	// $field->setErrorMessage("test error msg");
    	$field->addValidator(new RequiredValidator());
    	// $this->assertFalse($field->validate());
    	$field->setValue('asdf');
    	$this->assertEqual($field->validate(), true);
    }
}

$field = new Field();
$field->setName("testfeld");
$field->setLabel("TestFeld");
$field->setValue("hallo");

$field->validate();

echo $field->render();
