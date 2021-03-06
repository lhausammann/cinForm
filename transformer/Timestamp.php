<?php
// converts a timestamp to a date
class Transformer_Timestamp {
	protected $name = 'timestamp';
	public function getName() {
		return $this->name;
	}
	public function displayFormat($timestampOrInvalid, $field = null) {
		// only transform valid timestamps to the display format:
		$hasErrors = $field ? $field->hasErrors() : false;
		if ($hasErrors==false) {
			return date('d.m.Y', $timestampOrInvalid);
		} else {
			// if the field contains errors,
			// we show the unchanged value again. 
			return $timestampOrInvalid;
		}
		
	}
	
	public function storageFormat($date, $field = null) {
		// only transform valid fields to the storage format:
		$hasErrors = $field ? $field->hasErrors() : false;
		if ($hasErrors==false) {
			list ($day, $month, $year) = explode('.', $date);
			return mktime(0, 0, 0, $month, $day, $year);
		} 
		// if an invalid entry has been specified, we 
		// show it untransformed:
		return $date;
	}
}