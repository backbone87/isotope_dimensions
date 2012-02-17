<?php

class Dimension2DProductCallbacks extends Controller {
	
	public function callbackFormDimension2D($strField, $arrConfig, $objProduct) {
		if(!($objProduct instanceof Dimension2DProduct)) {
			return $arrConfig;
		}
		
		$arrConfig['eval']['unit'] = array_combine(array('x', 'y'), $objProduct->dimension_unit);
		$arrLabels = $objProduct->dimension_labels;
		$arrConfig['eval']['ordinateLabels'] = array(
			'x' => trim($arrLabels[0] . ' ' . $arrLabels[1]),
			'y' => trim($arrLabels[2] . ' ' . $arrLabels[3])
		);
		
		return $arrConfig;
	}

	public function saveDimensions($varValue, $objProduct) {
		if(!($objProduct instanceof Dimension2DProduct)) {
			return $varValue;
		}
		
		$objProduct->validateDim($varValue['x'], $varValue['y']);
		
		return $varValue;
	}
	
	public function saveConversion($varValue, $objDC) {
		$varValue = array_map('floatval', deserialize($varValue, true));
		
		$varValue[0] > 0 || $varValue[0] = '';
		$varValue[1] > 0 || $varValue[1] = '';
		
		return $varValue;
	}
	
	public function saveList($varValue, $objDC) {
		$this->import('Database');
		$objType = $this->Database->prepare(
			'SELECT	*
			FROM	tl_iso_producttypes
			WHERE	id = ?
			AND		`class` = ?'
		)->execute($objDC->type, 'bbit_iso_dimension_2d');
		
		if(!$objType->numRows) {
			return $varValue;
		}
		
		return $varValue;
	}
	
	public function saveRules($varValue, $objDC) {
		$varValue = deserialize($varValue, true);
		foreach($varValue as &$arrRule) {
			$this->adjustMinMaxValues($arrRule['x_min'], $arrRule['x_max']);
			$this->adjustMinMaxValues($arrRule['y_min'], $arrRule['y_max']);
			$this->adjustMinMaxValues($arrRule['area_min'], $arrRule['area_max']);
		}
		return $varValue;
	}
	
	protected function adjustMinMaxValues(&$fltMin, &$fltMax) {
		$fltMin = floatval($fltMin);
		$fltMax = floatval($fltMax);
		if($fltMin <= 0) {
			$fltMin = '';
		}
		if($fltMax <= 0) {
			$fltMax = '';
		} else {
			$fltMax = max($fltMax, $fltMin);
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
				$strStartStop .= sprintf($GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['labelStartStop'],
					$strStartDate,
					$strEndDate
				);
			
			} elseif (strlen($row['start'])) {
				$strStartStop .= sprintf($GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['labelStart'],
					$strStartDate
				);
			
			} else {
				$strStartStop .= sprintf($GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['labelStop'],
					$strEndDate
				);
			}
			$strStartStop .= ']</span>';
		}
		
		$objConfig = $this->Database->query(
			'SELECT	mode, unit
			FROM	tl_bbit_iso_dimension
			WHERE	id = ' . $row['pid']
		);

		switch($objConfig->mode) {
			case 'content':
					$strValue = number_format(
						$row['area'],
						0,
						$GLOBALS['TL_LANG']['MSC']['decimalSeparator'],
						$GLOBALS['TL_LANG']['MSC']['thousandsSeparator']
					) . $objConfig->unit;
				break;

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
			sprintf('system/themes/%s/images/%s', $this->getTheme(), $image),
			$strValue,
			$this->Isotope->formatPriceWithCurrency($row['price'], false),
			$strStartStop
		);
	}

	public function selectPalette($objDC) {
		if($this->Input->get('act') == 'create') {
			return;
		}
		
		$objConfig = $this->Database->prepare(
			'SELECT	mode, unit
			FROM	tl_bbit_iso_dimension
			WHERE	id IN (SELECT pid FROM tl_bbit_iso_dimension_price WHERE id = ?)'
		)->execute($objDC->id);

		$arrDCA = &$GLOBALS['TL_DCA']['tl_bbit_iso_dimension_price'];
		$arrDCA['palettes']['default'] = $arrDCA['palettes'][$objConfig->mode];

		switch($objConfig->mode) {
			case 'content':
				unset($arrDCA['fields']['dimension_x']);
				unset($arrDCA['fields']['dimension_y']);

				$arrDCA['fields']['content']['label'][0] .= " ({$objConfig->unit})";
				break;

			case 'dimension_2d':
			default:
				unset($arrDCA['fields']['content']);

				$arrDCA['fields']['dimension_x']['label'][0] .= " ({$objConfig->unit})";
				$arrDCA['fields']['dimension_y']['label'][0] .= " ({$objConfig->unit})";
				break;
		}
	}
	
	protected function __construct() {
		parent::__construct();
		$this->import('Database');
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

