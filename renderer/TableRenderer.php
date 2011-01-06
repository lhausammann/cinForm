<?php
require_once('../renderer/BaseRenderer.php');
class TableRenderer extends BaseRenderer {
	public function renderElement($element) {
		return "<tr><td>" . $element->getLabel() . "</td><td>" . parent::renderElement($element) . "</td></tr>";
	}
}