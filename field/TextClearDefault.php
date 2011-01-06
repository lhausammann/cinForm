<?php
class TextClearDefault extends Field {
	
	public function defaultHtml() {
		$empty = "''";
		$default = "'" . addslashes($this->defaultValue) . "'";
		$js = 'onblur="if (this.value==' . $empty .') this.value='.$default . '"';
		$js .= ' onclick="if (this.value==' . $default .') this.value='.$empty . '"';
		$return = "<input type='" . $this->type . "' name='" . $this->name . "' id='".$this->name . "' class='" . $this->compileCss() . "' value='" . $this->toDisplayFormat($this->getValue()) . "' " . $js." />";
		return $return;
	}
}