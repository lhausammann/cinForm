<?php
require_once('../renderer/BaseRenderer.php');
class RedBorderRenderer extends BaseRenderer {
	public function renderElement($element) {
		$html = "<div style='border:1px solid red;margin:5px;'>" . parent::renderElement($element) . '</div>';
		return $html;
	}
}