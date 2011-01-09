<?php
class Renderer_RedBorder extends Renderer_Base {
	public function renderElement($element) {
		$html = "<div style='border:1px solid red;margin:5px;'>" . parent::renderElement($element) . '</div>';
		return $html;
	}
}