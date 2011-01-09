<?php
class Renderer_ClearDefault extends Renderer_Base {
	// replace base implementation on text fields:
	public function renderElement($element) {
		if ($element->getType()=='text') {
			echo 'clearDefault' . $element->getType();
			$empty = "''";
			$default = "'" . addslashes($element->getDefaultValue()) . "'";
			$js = 'onblur="if (this.value==' . $empty .') this.value='.$default . '"';
			$js .= ' onclick="if (this.value==' . $default .') this.value='.$empty . '"';
			
			$return = "<input style='background-color:yellow;' type='" . $element->getType() . "' name='" . $element->getName() . "' id='".$element->getName() . "' class='" . $element->compileCss() . "' value='" . $element->toDisplayFormat($element->getValue()) . "' " . $js." />";
			return $return;
		} else {
			// fall back to the default implementation:
			return parent::renderElement($element);
		}
	}
}