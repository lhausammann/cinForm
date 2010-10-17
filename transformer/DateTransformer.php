<?php
// converts a timestamp to a date
class DateTransformer {
	public function displayFormat($timestamp) {
		return date('d.m.Y', $timestamp);
	}
	
	public function storageFormat($date) {
		list ($day, $month, $year) = explode('.', $date);
		return mktime(0,0,0, $month, $day,$year);
	}
}