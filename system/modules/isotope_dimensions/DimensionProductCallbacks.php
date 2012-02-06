<?php

class DimensionProductCallbacks extends Controller {
	
	public function addPriceToProduct($objTemplate, $objProduct) {
		if(!($objProduct instanceof DimensionProduct)) {
			return $objTemplate;
		}
		
		$objTemplate->price = '<div class="iso_attribute" id="' . $objProduct->formSubmit . '_price">'
			. $objProduct->formatted_available_price
			. '</div>';

		return $objTemplate;
	}
	
	public function injectFormDimensionUnit($strField, $arrConfig, $objProduct) {
		if(!($objProduct instanceof DimensionProduct)) {
			return $arrConfig;
		}
		
		$arrConfig['eval']['unit'] = array_combine(array('x', 'y'), $objProduct->dimension_unit);
		
		return $arrConfig;
	}
	
	public function saveDimensions($varValue, $objProduct) {
		if(!($objProduct instanceof DimensionProduct)) {
			return $varValue;
		}
		
		$objProduct->validateDimension($varValue['x'], $varValue['y']);
		
		return $varValue;
	}
	
	public function saveList($varValue, $objDC) {
		$this->import('Database');
		$objType = $this->Database->prepare(
			'SELECT	*
			FROM	tl_iso_producttypes
			WHERE	id = ?
			AND		`class` = ?'
		)->execute($objDC->type, 'bbit_iso_dimension');
		
		if(!$objType->numRows) {
			return $varValue;
		}
		
		return $varValue;
	}
	
	public function saveArea($varValue, $objDC) {
		$varValue = deserialize($varValue, true);
		$this->adjustMinMaxValues($varValue[0], $varValue[1]);
		return $varValue;
	}
	
	public function saveRules($varValue, $objDC) {
		$varValue = deserialize($varValue, true);
		foreach($varValue as &$arrRule) {
			$this->adjustMinMaxValues($arrRule['x_min'], $arrRule['x_max']);
			$this->adjustMinMaxValues($arrRule['y_min'], $arrRule['y_max']);
		}
		return $varValue;
	}
	
	protected function adjustMinMaxValues(&$fltMin, &$fltMax) {
		if(!strlen($fltMin) || $fltMin <= 0) {
			$fltMin = '';
		}
		if(strlen($fltMax)) {
			if(strlen($fltMin) && $fltMax < $fltMin) {
				$fltMax = $fltMin;
			} elseif($fltMax <= 0) {
				$fltMax = '';
			}
		}
	}
	
	public function listPrice($row) {
		$this->import('Isotope');

		if(!$row['published']
		|| (strlen($row['start']) && $row['start'] > time())
		|| (strlen($row['stop']) && $row['stop'] < time())) {
			$image = 'unpublished.gif';
		} else {
			$image = 'published.gif';
		}

		if(strlen($row['start']) || strlen($row['stop'])) {
			$strStartDate	= $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $row['start']);
			$strEndDate		= $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $row['stop']);
			$strStartStop	= ' <span style="color:#b3b3b3; padding-left:3px;">[';
			if(strlen($row['start']) && strlen($row['stop'])) {
				$strStartStop .= sprintf($GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['labelStartStop'],
					$strStartDate,
					$strEndDate
				);
			
			} elseif (strlen($row['start'])) {
				$strStartStop = sprintf($GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['labelStart'],
					$strStartDate
				);
			
			} else {
				$strStartStop = sprintf($GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['labelStop'],
					$strEndDate
				);
			}
			$strStartStop .= ']</span>';
		}
		
		$objConfig = $this->Database->execute("SELECT mode, unit FROM tl_iso_product_dimensions WHERE id={$row['pid']}");

		switch($objConfig->mode) {
			/*case 'area':
					$strValue = number_format(
						$row['area'],
						0,
						$GLOBALS['TL_LANG']['MSC']['decimalSeparator'],
						$GLOBALS['TL_LANG']['MSC']['thousandsSeparator']
					) . $objConfig->unit;
				break;*/

			case 'dimension_2d':
			default:
				$strValue = number_format(
					$row['dimension_x'],
					0,
					$GLOBALS['TL_LANG']['MSC']['decimalSeparator'],
					$GLOBALS['TL_LANG']['MSC']['thousandsSeparator']
				) . $objConfig->unit;
				$strValue .= ' x ';
				$strValue .= number_format(
					$row['dimension_y'],
					0,
					$GLOBALS['TL_LANG']['MSC']['decimalSeparator'],
					$GLOBALS['TL_LANG']['MSC']['thousandsSeparator']
				) . $objConfig->unit;
				
				break;
		}
		
		return sprintf('<div class="list_icon" style="background-image:url(%s);">%s: %s%s</div>',
			$this->generateImage($image),
			$strValue,
			$this->Isotope->formatPriceWithCurrency($row['price'], false),
			$strStartStop
		);
	}

	public function selectPalette($objDC) {
		if($this->Input->get('act') == 'create') {
			return;
		}
		
		$objConfig = $this->Database->execute(
			'SELECT	mode, unit
			FROM	tl_iso_product_dimensions
			WHERE	id IN (SELECT pid FROM tl_iso_product_dimension_prices WHERE id = ?)'
		)->execute($objDC->id);

		$arrDCA = &$GLOBALS['TL_DCA']['tl_iso_product_dimension_prices'];
		$arrDCA['palettes']['default'] = $arrDCA['palettes'][$objConfig->mode];

		switch($objConfig->mode) {
			/*case 'area':
				unset($arrDCA['fields']['dimension_x']);
				unset($arrDCA['fields']['dimension_y']);

				$arrDCA['fields']['area']['label'][0] .= " ({$objConfig->unit})";
				break;*/

			case 'dimension_2d':
			default:
				unset($arrDCA['fields']['area']);

				$arrDCA['fields']['dimension_x']['label'][0] .= " ({$objConfig->unit})";
				$arrDCA['fields']['dimension_y']['label'][0] .= " ({$objConfig->unit})";
				break;
		}
	}
	
	protected function __construct() {
		parent::__construct();
	}
	
	protected function __clone() {
	}
	
	private static $objInstance;
	
	public static function getInstance() {
		if(isset(self::$objInstance)) {
			return self::$objInstance;
		}
		return self::$objInstance = new self();
	}
	
}

