<?php
require_once('Field.php');
require_once('../validator/RegexValidator.php');
require_once('../transformer/DateTransformer.php');
require_once('../transformer/TimestampTransformer.php');

class DateField extends Field {
	public function init() {
		$this->addValidator (new RegexValidator("/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/", "Date has to be in the format: dd.mm.yyyy"));
		$this->addTransformer (new TimestampTransformer());
	}
	
	// @TODO; only set defaults ($form->setDefaults)on the form, do not handle in fields.
	// this prevents problem when adding/removing transforers at runtime.
	// timestamp or date
	public function setStorageFormat($timestampOrDate) {
		echo $this->defaultValue;
		if ($timestampOrDate == 'timestamp') {
			$this->removeTransformer('date');
			$this->removeTransformer('timestamp');
			$this->addTransformer(new TimestampTransformer()); 
			$this->setDefaultValue($this->defaultValue);
		} else if ($timestampOrDate=='date') {
			$this->removeTransformer('timestamp');
			$this->removeTransformer('date');
			$this->addTransformer(new DateTransformer());
			$this->setDefaultValue($this->defaultValue);
		} else {
			throw new Exception ("Invalid parameter: must be 'timestamp' or 'date'.");
		}
	}
}