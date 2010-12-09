<?php 
class DefaultRenderer {

	protected $renderer = null;
	
	
	public function __construct($renderer = null) {
		$this->renderer = $renderer;
	} 
	
	protected function defaultHtml($element) {
		if ($this->renderer) {
			return $this->renderer->defaultHtml($element);
		} else {
			return $element->defaultHtml();
		}
	}
	
	protected function toHtml($element) {
		if ($this->renderer) {
			return $this->renderer->toHtml($element);
		} else {
			return $this->defaultHtml($element);
		}
	}
	
/** get the html for a field including its attributes:
	 * just return the rendered field and its value
	 
	public function toHtml($element) {
		$return .= "<input type='" . $element->getType() . "' name='" . $element->getName() . "' id='".$element->getName() . "' class='" . $element->compileCss() . "' value='" . $element->toDisplayFormat($element->getValue()) . "' />";
		return $return;
	}
	*/
	
	/*
	 * renders the whole field, including surrounding validator js config asf.
	 * Only override if really necessary - use toHtml() instead.
	 */
	public function render($element) {
		return $this->toHtml($element);
	}
}