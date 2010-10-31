<?php
// converts a timestamp to a date
class DateTransformer {
	
	public function displayFormat($timestamp, $field) {
		if ($field->hasErrors()==false) {
			echo 'transforming timestamp to display: ' . $timestamp;
			return date('d.m.Y', $timestamp);
		} else {
			// if the field has been filled out with errors,
			// we show it again. (in this, case, timerstamp looks lie "17.03",
			// and processing it will result in something like 01.01.1970 
			echo 'field has errors';
			echo $timestamp;
			return $timestamp;
		}
		
	}
	
	public function storageFormat($date, $field) {
		echo "transforming date to storage: " . $date;
		// only transform valid fields to the storage format:
		if ($field->hasErrors()==false) {
			list ($day, $month, $year) = explode('.', $date);
			return mktime(0, 0, 0, $month, $day, $year);
		} 
		// if an invalid entry has been specified, we 
		// show it untransformed:
		echo $date;
		return $date;
	}
}