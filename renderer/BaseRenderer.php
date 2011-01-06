<?php
/*
 * Base renderer class:
 * 
 */

interface Renderer {
	// return element as html:
	public function renderElement($element);
}


class BaseRenderer {
	
	

	protected $renderer = null;
	
	public function __construct($renderer = null) {
		$this->renderer = $renderer;
	} 
	
	
	protected function renderElement($element) {
		if ($this->renderer) {
			return $this->renderer->renderElement($element);
		} else {
			return $element->toHtml();
		}
	}
}