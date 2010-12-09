<?php
require_once('../renderer/DefaultRenderer.php');
class RedBorderRenderer extends DefaultRenderer {
	public function toHtml($element) {

		if ($this->renderer) {
			$html = $this->renderer->toHtml($element);
		} else {
			$html = $this->defaultHtml($element);
		}
		$html = "<div style='border:1px solid red;'>" . $html . '</div>';
		return $html;
	}
}