<?php
// converts a timestamp to a date
class Transformer_Date {
	protected $name = 'date';
	public function getName() {
		return $this->name;
	}
	
	public function displayFormat($dateOrInvalid, $field) {
		// only transform valid mysql to the display format:
		// (1980-17-03 => 17.03.1980)
		
		if ($field->hasErrors()==false) {
			list ($year, $month, $day) = explode('-', $dateOrInvalid);
			//return mktime(0, 0, 0, $month, $day, $year);
			$return = $day . '.' . $month. '.' . $year;
			return ($day . '.' . $month. '.' . $year);
			
		} else {
			
			
			// if the field contains errors,
			// we show the unchanged value again. 
			return $dateOrInvalid;
		}
		
	}
	
	public function storageFormat($date, $field) {
		// only transform valid fields to the storage format:
		if ($field->hasErrors()==false) {
			list ($day, $month, $year) = explode('.', $date);
			//return mktime(0, 0, 0, $month, $day, $year);
			$return = $year.'-'.$month.'-'.$day;
			return $return;
		} 
		// if an invalid entry has been specified, we 
		// show it untransformed:
		return $date;
	}
}