<?php

class Field_TextArea extends Field_Base {
	protected $rte = false;
	protected $rows = 10;
	protected $cols = 30;
	protected $type='textarea';
	
	
	
	
public function defaultHtml() {
		$html = "<textarea id='".$this->name."' name='".$this->name . "' class='" . $this->compileCss() . "' rows='".$this->rows."' cols='".$this->cols."' >";
		$html .= $this->toDisplayFormat($this->getValue());
		$html .= "</textarea>";
		return $html;
	}
	
	public function setRows($rows) {
		$this->rows = $rows;
	} 
	public function setCols($cols) {
		$this->cols = $cols;
	}
	
	public function setRte($rte) {
		$this->rte = $rte;
		if ($rte) {
			$this->addClass('rte');
			$this->removeTransformer('entity');
		}
	}
}