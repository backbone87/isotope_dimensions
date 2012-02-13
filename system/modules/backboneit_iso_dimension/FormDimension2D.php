<?php

class FormDimension2D extends Widget {

	protected $blnSubmitInput = true;

	protected $strTemplate = 'form_dimension_2d';
	
	public function __construct($arrAttributes = false) {
		$this->arrConfiguration['rgxp'] = 'digit';
		parent::__construct($arrAttributes);
	}

	public function __set($strKey, $varValue) {
		switch ($strKey) {
			case 'rgxp':
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}

	public function __get($strKey) {
		switch ($strKey) {
			default:
				return parent::__get($strKey);
				break;
		}
	}
	
	protected function validator($varInput) {
		$varInput = parent::validator($varInput);
		if($this->hasErrors()) {
			$this->blnSubmitInput = false;
		}
		return $varInput;
	}
	
	public function parse($arrAttributes = null) {
		$this->addAttributes($arrAttributes);
		return $this->generate();
	}

	public function generate() {
		if($this->strTemplate == '') {
			return '';
		}
		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/backboneit_iso_dimension/html/formdimensions.js';
		ob_start();
		include $this->getTemplate($this->strTemplate, $this->strFormat);
		return ob_get_clean();
	}
}
