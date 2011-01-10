<?php
require_once('../simpletest/autorun.php');
require_once('../../transformer/Timestamp.php');

class TransformerTestCase extends UnitTestCase {
	function testDateTransformer() {
		$transformer = new Transformer_Timestamp();
		
		$this->assertEqual($transformer->displayFormat(mktime(0,0,0,3,17,1980)),'17.03.1980');
		$this->assertEqual($transformer->displayFormat(mktime(0,0,0,3,17,2010)),'17.03.2010');
		$this->assertEqual($transformer->displayFormat($transformer->storageFormat('17.03.1980')), '17.03.1980');
		
	}
}
	