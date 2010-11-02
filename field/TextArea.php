<?php
require_once('Field.php');
class TextArea extends Field {
	protected $rte = false;
	protected $rows = 10;
	protected $cols = 30;
	
	
	
public function toHtml() {
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
			$this->cssClasses[] = 'rte';
		}
	}
}