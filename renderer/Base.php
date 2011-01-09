<?php
/*
 * Base renderer class:
 * 
 */

interface Renderer {
	// render a form as html:
	public function renderElement($element);
	public function renderBeginForm($form);
	public function renderEndForm($form);
}

/**
 * the base implementation of the renderer renders a form as html.
 * If there is an other registered, it delegates the rendering to it, otherwise
 * it uses the form and form element default methods for rendering.
 * @author luz
 *
 */
class Renderer_Base implements Renderer {
	
	protected $renderer = null;
	
	public function __construct($renderer = null) {
		$this->renderer = $renderer;
	} 
	
	public function renderBeginForm($form) {
		if ($this->renderer) {
			return $this->renderer->renderBeginForm($form);
		}
		return $form->startForm();
	}
	
	public function renderEndForm($form) {
		if ($this->renderer) {
			return $this->rnederer->renderEndForm($form);
		} 
		return $form->endForm();
	} 
	
	public function renderElement($element) {
		if ($this->renderer) {
			return $this->renderer->renderElement($element);
		} else {
			// fall back to the element default render method:
			return $element->toHtml();
		}
	}
}