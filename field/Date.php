<?php


class Field_Date extends Field_Base {
	public function init() {
		parent::init();
		$this->addValidator (new Validator_Regex("/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/", "Date has to be in the format: dd.mm.yyyy"));
		$this->addTransformer (new Transformer_Timestamp());
	}
	
	public function setStorageFormat($timestampOrDate) {
		//echo $this->getDefaultValue();
		if ($timestampOrDate == 'timestamp') {
			$this->removeTransformer('date');
			$this->removeTransformer('timestamp');
			$this->addTransformer(new Transformer_Timestamp()); 
			$this->setDefaultValue($this->defaultValue);
		} else if ($timestampOrDate=='date') {
			$this->removeTransformer('timestamp');
			$this->removeTransformer('date');
			$this->addTransformer(new Transformer_Date());
			$this->setDefaultValue($this->defaultValue);
		} else {
			throw new Exception ("Invalid parameter: must be 'timestamp' or 'date'.");
		}
	}
}