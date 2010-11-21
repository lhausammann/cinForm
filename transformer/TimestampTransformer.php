<?php
// converts a timestamp to a date
class TimestampTransformer {
	protected $name = 'timestamp';
	public function getName() {
		return $this->name;
	}
	public function displayFormat($timestampOrInvalid, $field) {
		// only transform valid timestamps to the display format:
		if ($field->hasErrors()==false) {
			return date('d.m.Y', $timestampOrInvalid);
		} else {
			// if the field contains errors,
			// we show the unchanged value again. 
			return $timestampOrInvalid;
		}
		
	}
	
	public function storageFormat($date, $field) {
		// only transform valid fields to the storage format:
		if ($field->hasErrors()==false) {
			list ($day, $month, $year) = explode('.', $date);
			return mktime(0, 0, 0, $month, $day, $year);
		} 
		// if an invalid entry has been specified, we 
		// show it untransformed:
		return $date;
	}
}