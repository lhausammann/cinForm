<?php
class Renderer_Table extends Renderer_Base {
	public function renderBeginForm($form) {
		return '<table>' . parent::renderBeginForm($form);
	}
	
	public function renderElement($element) {
		return "<tr><td>" . $element->getLabel() . "</td><td>" . parent::renderElement($element) . "</td></tr>";
	}
	public function renderEndForm($form) {
		return "</table>";
	}
	
}