<?php
require_once('../renderer/DefaultRenderer.php');
class DivRenderer extends DefaultRenderer {
	public function toHtml($element) {

		if ($this->renderer) {
			$html = $this->renderer->toHtml($element);
		} else {
			$html = $this->defaultHtml($element);
		}
		$hasErrorCss = $elelement->hasErrors() ? " error " : "";
		$return = "<div class='wrapper " . $element->getName() . " " . $hasErrorCss . "'>";
		$return .= $element->labelHtml();
		$return .="<a name='". $this->getName()."'></a>";
		$return.=$this->toHtml($element);
		
		if ($this->hasErrors()) {
			$return .= "<span class='error'>" . $this->getError() . "</span>";
		}
		
		$return .= "</div>";
		return $return;
		return $html;
	}
}