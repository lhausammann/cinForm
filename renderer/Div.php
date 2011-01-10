<?php
class Renderer_Div extends Renderer_Base {
	public function renderElement($element) {
		$hasErrorCss = $element->hasErrors() ? " error " : "";
		$return = "<div class='wrapper " . $element->getName() . " " . $hasErrorCss . "'>";
		$return .= $element->labelHtml();
		$return .="<a name='". $element->getName()."'></a>";
		$return .= parent::renderElement($element);
		
		if ($element->hasErrors()) {
			$return .= "<span class='error'><font color=red>" . $element->getError() . "</font></span>";
		}
		
		$return .= "</div>";
		return $return;
	}
}