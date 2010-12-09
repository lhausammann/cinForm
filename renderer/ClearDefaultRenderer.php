
<?php
require_once('../renderer/DefaultRenderer.php');
class ClearDefaultRenderer extends DefaultRenderer {
	public function toHtml($element) {
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
			echo 'using default';
			
			return $this->defaultHtml($element);
		}
	}
}