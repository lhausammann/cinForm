<?php 

define( APPLICATION_DIR, '/Form/');
define (ENABLE_AUTOLOAD, true);

require_once('../../Form.class.php');
require_once('../simpletest/autorun.php');


class FieldTestCase extends UnitTestCase {
    function FieldTestCase() {
        $this->UnitTestCase('Field test');
    }
    
    function setUp() {
    }
    
    function tearDown() {
    }
    
    function testFieldValidatesWithoutValidatorsSet() {
		$field = new Field_Base('test');
		
		$field->setValue("fieldvalue");
		
		$field->setLabel("Field 1");
		$field->setName("field");
		
		$field->validate();
		$html = $field->render();
		echo $html;
		
		$this->assertEqual($field->getErrors(), array());
    }
    
    function testRequiredField() {
    	$field = new Field_Base('test');
    	// $field->setErrorMessage("test error msg");
    	$field->addValidator(new Validator_Required());
    	// $this->assertFalse($field->validate());
    	$field->setValue('asdf');
    	$this->assertEqual($field->validate(), true);
    }
}

$field = new Field_Base('test');
$field->setName("testfeld");
$field->setLabel("TestFeld");
$field->setValue("hallo");

$field->validate();

echo $field->render();
