<?php

class FormDimension2D extends Widget {

	protected $blnSubmitInput = true;

	protected $strTemplate = 'form_dimension_2d';
	
	public function __construct($arrAttributes = false) {
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
		$varInput = array_slice(deserialize($varInput, true), 0, 2);
		
		if(2 !== count(array_filter($varInput, 'strlen'))) {
			$this->addError($GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_errMandatory']);
		}
		
		foreach($varInput as &$fltInput) {
			$fltInput = str_replace(',', '.', $fltInput);
		}
		
		if(2 !== count(array_filter($varInput, 'is_numeric'))) {
			$this->addError($GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_errPositive']);
		}
		
		$varInput = array_map('floatval', $varInput);
		
		foreach($varInput as $fltInput) {
			if($fltInput <= 0) {
				$this->addError($GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_errPositive']);
				break;
			}
		}
		
		if($this->hasErrors()) {
			$this->blnSubmitInput = false;
			return '';
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
