<?php

class DimensionProduct extends IsotopeProduct {

	public function __construct($arrData, $arrOptions=null, $blnLocked=false) {
		if($arrOptions) {
			$arrOptions = array_merge(array('bbit_iso_dimension_input' => null), $arrOptions);
		}
		parent::__construct($arrData, $arrOptions, $blnLocked);
//		$this->arrAttributes = array_merge(array('bbit_iso_dimension_input'), (array) $this->arrAttributes);
		$this->arrVariantAttributes = array_merge(array('bbit_iso_dimension_input'), (array) $this->arrVariantAttributes);
	}

	public function __get($strKey) {
		switch($strKey) {
			case 'dimension_x':
				return floatval($this->arrOptions['bbit_iso_dimension_input']['x']);
				break;
				
			case 'dimension_y':
				return floatval($this->arrOptions['bbit_iso_dimension_input']['y']);
				break;
				
			case 'dimension_input':
				return $this->dimension_x && $this->dimension_y;
				break;
				
			case 'dimension_unit':
				return deserialize($this->arrData['bbit_iso_dimension_inputUnit'], true);
				break;
				
			case 'dimension_list':
				return intval($this->arrData['bbit_iso_dimension_list']);
				break;
				
			case 'dimension_conversion':
				$arrConv = deserialize($this->arrData['bbit_iso_dimension_inputConversion'], true);
				$arrConv[0] = strlen($arrConv[0]) ? floatval($arrConv[0]) : 1.0;
				$arrConv[1] = strlen($arrConv[1]) ? floatval($arrConv[1]) : 1.0;
				return $arrConv;
				break;
				
			case 'dimension_area':
				return deserialize($this->arrData['bbit_iso_dimension_area'], true);
				break;
				
			case 'dimension_rules':
				return deserialize($this->arrData['bbit_iso_dimension_rules'], true);
				break;

			case 'price':
				return $this->blnLocked ? $this->arrData['price'] : $this->Isotope->calculatePrice($this->findDimensionPrice(), $this, 'price', $this->arrData['tax_class']);
				break;

			case 'min_price':
				return $this->blnLocked ? $this->arrData['price'] : $this->Isotope->calculatePrice($this->findDimensionPrice(true), $this, 'price', $this->arrData['tax_class']);
				break;
				
			case 'formatted_available_price':
				$fltPrice = $this->price;
				if($fltPrice) {
					return $this->Isotope->formatPriceWithCurrency($fltPrice);
				} else {
					return sprintf($GLOBALS['TL_LANG']['MSC']['priceRangeLabel'], $this->Isotope->formatPriceWithCurrency($this->min_price));
				}
				break;

			case 'tax_free_price':
				return $this->blnLocked ? $this->arrData['price'] : $this->Isotope->calculatePrice($this->findDimensionPrice(), $this, 'price');
				break;
		}

		return parent::__get($strKey);
	}
	
	public function generate($strTemplate, &$objModule) {
		$this->injectAttribute(true);
		$strReturn = parent::generate($strTemplate, $objModule);
		$this->injectAttribute(false);
		return $strReturn;
	}

	public function generateAjax(&$objModule) {
		$this->injectAttribute(true);
		$arrOptions = parent::generateAjax($objModule);
		$this->injectAttribute(false);

		$arrOptions[] = array(
			'id'	=> $this->formSubmit . '_price',
			'html'	=> '<div class="iso_attribute" id="' . $this->formSubmit . '_price">'
				. $this->formatted_available_price
				. '</div>'
		);

		return $arrOptions;
	}

	private function findDimensionPrice($blnMinPrice = false) {
		$time = time();
	
		$arrDimensionData = $this->getDimensionData();
		if(!$arrDimensionData['list']) {
			return 0;
		}
				
		if($blnMinPrice) {
			if($this->dimension_input) {
				$arrConditions = array();
				foreach($arrDimensionData['list'] as $intID => $intListID) {
					$fltX = $this->dimension_x * $arrDimensionData['conversion'][$intID][0];
					$fltY = $this->dimension_y * $arrDimensionData['conversion'][$intID][1];
					$arrConditions[] = 'p.pid = ' . $intListID . ' AND p.dimension_x >= ' . $fltX . ' AND p.dimension_y >= ' . $fltY;
				}
				$strConditions = '(' . implode(') OR (', array_unique($arrConditions)) . ')';
			
				$arrParams[] = 'dimensions';
				
				$objPrice = $this->Database->prepare('
					SELECT	MIN(p.price) AS price
					FROM	tl_iso_product_dimension_prices AS p
					WHERE	(' . $strConditions . ')
					AND		(SELECT mode FROM tl_iso_product_dimensions AS d WHERE d.id = p.pid) = ?
					AND		p.published = \'1\'
					AND		(p.start = \'\' OR p.start > ' . $time . ')
					AND		(p.stop = \'\' OR p.stop < ' . $time . ')
					ORDER BY p.price
				')->execute($arrParams);
				

			} else {
				$arrConditions = array();
				foreach($arrDimensionData['list'] as $intID => $intListID) {
					$strList = 'p.pid = ' . $intListID;
					$arrRules = array();
					foreach($arrDimensionData['rules'][$intID] as $arrRule) {
						if($arrRule['min_x'] <= 0 && $arrRule['min_y'] <= 0) {
							$arrRules = array($strList);
							break;
						}
						
						$strRuleCondition = '';
						$strConjuction = '';
						
						if($arrRule['min_x'] > 0) {
							$strRuleCondition .= 'p.dimension_x >= ' . floatval($arrRule['min_x']);
							$strConjuction = ' AND ';
						}
						
						if($arrRule['min_y'] > 0) {
							$strRuleCondition .= $strConjuction . 'p.dimension_y >= ' . floatval($arrRule['min_y']);
						}
						
						if($strRuleCondition) {
							$arrRules[] = $strRuleCondition . ' AND ' . $strList;
						}
					}
					$arrConditions[] = $arrRules ? $arrRules : array($strList);
				}
				$strConditions = '(' . implode(') OR (', array_unique(call_user_func_array('array_merge', $arrConditions))) . ')';
				
				$arrParams[] = 'dimensions';
				
				$objPrice = $this->Database->prepare('
					SELECT	MIN(p.price) AS price
					FROM	tl_iso_product_dimension_prices AS p
					WHERE	(' . $strConditions . ')
					AND		(SELECT mode FROM tl_iso_product_dimensions AS d WHERE d.id = p.pid) = ?
					AND		p.published = \'1\'
					AND		(p.start = \'\' OR p.start > ' . $time . ')
					AND		(p.stop = \'\' OR p.stop < ' . $time . ')
					ORDER BY p.price
				')->execute($arrParams);
			}

		} elseif($this->dimension_input && (!$this->arrType['variants'] || $this->pid != 0)) {
			$arrParams[] = $this->dimension_list;
			$arrParams[] = 'dimensions';
			$arrParams[] = $this->dimension_x * $this->dimension_conversion[0];
			$arrParams[] = $this->dimension_y * $this->dimension_conversion[1];
			
			$objPrice = $this->Database->prepare('
				SELECT	MIN(p.price) AS price
				FROM	tl_iso_product_dimension_prices AS p 
				WHERE	p.pid = ?
				AND		(SELECT mode FROM tl_iso_product_dimensions AS d WHERE d.id = p.pid) = ?
				AND		p.dimension_x >= ?
				AND		p.dimension_y >= ?
				AND		p.published = \'1\'
				AND		(p.start = \'\' OR p.start > ' . $time . ')
				AND		(p.stop = \'\' OR p.stop < ' . $time . ')
			')->execute($arrParams);
		}
		
		return $objPrice && $objPrice->numRows ? $objPrice->price : 0;
	}
	
	public function validateDimension($fltX, $fltY) {
		if($fltX < 0 || $fltY < 0) {
			throw new Exception($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_positive']);
		}
		
		$time = time();
		
		$arrDimensionData = $this->getDimensionData();
		
		$arrParams[] = 'dimensions';
		$objMaxValues = $this->Database->prepare('
			SELECT	p.pid, MAX(p.dimension_x) AS max_x, MAX(p.dimension_y) AS max_y
			FROM	tl_iso_product_dimension_prices AS p 
			WHERE	p.pid IN (' . implode(',', array_unique($arrDimensionData['list'])) . ')
			AND		(SELECT mode FROM tl_iso_product_dimensions AS d WHERE d.id = p.pid) = ?
			AND		p.published = \'1\'
			AND		(p.start = \'\' OR p.start > ' . $time . ')
			AND		(p.stop = \'\' OR p.stop < ' . $time . ')
			GROUP BY p.pid
		')->execute($arrParams);
	
		if(!$objMaxValues->numRows) {
			throw new Exception($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_noPrices']);
		}
		
		while($objMaxValues->next()) {
			$arrMaxValues[$objMaxValues->pid] = array('x' => $objMaxValues->max_x, 'y' => $objMaxValues->max_y);
		}
		
		foreach($arrDimensionData['conversion'] as $intID => $arrConv) {
			$arrMaxValue = $arrMaxValues[$arrDimensionData['list'][$intID]];
			$fltListMaxX = $arrMaxValue['x'] / $arrConv[0];
			$fltListMaxY = $arrMaxValue['y'] / $arrConv[1];
			
			if($fltX > $fltListMaxX && $fltY > $fltListMaxY) {
				continue;
			} elseif($fltX <= $fltListMaxX && $fltY <= $fltListMaxY) {
				$blnListMaxOK = true;
			}
			
			$blnAreaOK = false;
			$fltArea = $fltX * $fltY * $arrConv[1] / $arrConv[0];
			$fltRuleMinArea = $arrDimensionData['area'][$intID][0];
			$fltRuleMaxArea = $arrDimensionData['area'][$intID][1];
			$blnMinAreaOK = strlen($fltRuleMinArea) ? $fltArea >= $fltRuleMinArea : true;
			$blnMaxAreaOK = strlen($fltRuleMaxArea) ? $fltArea <= $fltRuleMaxArea : true;
			if($blnMinAreaOK && $blnMaxAreaOK) {
				$blnAreaOK = true;
			} else {
				if(!$blnMinAreaOK) {
					$fltMinArea = isset($fltMinArea) ? min($fltMinArea, $fltRuleMinArea) : $fltRuleMinArea;
				}
				if(!$blnMaxAreaOK) {
					$fltMaxArea = isset($fltMaxArea) ? min($fltMaxArea, $fltRuleMaxArea) : $fltRuleMaxArea;
				}
			}
			
			foreach($arrDimensionData['rules'][$intID] as $arrRule) {
				$fltRuleMinX = min(max(0, $arrRule['x_min']), $fltListMaxX);
				$fltRuleMinY = min(max(0, $arrRule['y_min']), $fltListMaxY);
				$fltRuleMaxX = strlen($arrRule['x_max']) ? min(max($arrRule['x_max'], $fltRuleMinX), $fltListMaxX) : $fltListMaxX;
				$fltRuleMaxY = strlen($arrRule['y_max']) ? min(max($arrRule['y_max'], $fltRuleMinY), $fltListMaxY) : $fltListMaxY;
				
				$blnXOK = $fltX >= $fltRuleMinX && $fltX <= $fltRuleMaxX;
				$blnYOK = $fltY >= $fltRuleMinY && $fltY <= $fltRuleMaxY;
				if($blnXOK && $blnYOK && $blnAreaOK) {
					return;
				}
				if($blnXOK) {
					$fltMinY = isset($fltMinY) ? min($fltMinY, $fltRuleMinY) : $fltRuleMinY;
					$fltMaxY = isset($fltMaxY) ? max($fltMaxY, $fltRuleMaxY) : $fltRuleMaxY;
				}
				if($blnYOK) {
					$fltMinX = isset($fltMinX) ? min($fltMinX, $fltRuleMinX) : $fltRuleMinX;
					$fltMaxX = isset($fltMaxX) ? max($fltMaxX, $fltRuleMaxX) : $fltRuleMaxX;
				}
			}
		}
	
		$arrUnit = $this->dimension_unit;
		$strError = '';
		if(isset($fltMaxX)) {
			if($fltMinX > 0) {
				$strError .= sprintf($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_xAdjustMinMax'], $fltY . $arrUnit[1], $fltMinX . $arrUnit[0], $fltMaxX . $arrUnit[0]);
			} else {
				$strError .= sprintf($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_xAdjustMax'], $fltY . $arrUnit[1], $fltMaxX . $arrUnit[0]);
			}
			$strConjunction = '<br />';
		}
		if(isset($fltMaxY)) {
			$strError .= $strConjunction;
			if($fltMinY > 0) {
				$strError .= sprintf($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_yAdjustMinMax'], $fltX . $arrUnit[0], $fltMinY . $arrUnit[1], $fltMaxY . $arrUnit[1]);
			} else {
				$strError .= sprintf($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_yAdjustMax'], $fltX . $arrUnit[0], $fltMaxY . $arrUnit[1]);
			}
		}
		if(!$strError) {
			/*if(!$blnListMaxOK) {
				$strError = $GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_noSizePrices'];
				
			} elseif(!$blnAreaOK) {
				$strError = sprintf($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_area'], $fltMinArea . $arrUnit[0], $fltMaxArea . $arrUnit[0]);
				
			} else*/ {
				$strError = $GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_noSizePrices'];
				
			}
		}
		
		throw new Exception($strError);
	}
	
	protected $arrDimensionData;
	
	protected function getDimensionData() {
		if(isset($this->arrDimensionData)) {
			return $this->arrDimensionData;
		}
		
		$arrDimensionVariants = array_intersect(
			array(
				'bbit_iso_dimension_rules',
				'bbit_iso_dimension_conversion',
				'bbit_iso_dimension_list',
				'bbit_iso_dimension_area'
			),
			$this->arrVariantAttributes
		);
		
		$this->arrDimensionData = array();
		
		if($this->arrType['variants'] && $this->pid == 0 && $arrDimensionVariants) {
			$objVariant = clone $this;
			$blnRulesVariant = in_array('bbit_iso_dimension_rules', $arrDimensionVariants);
			$blnRulesVariant || $arrRules = $this->dimension_rules;
			$blnAreaVariant = in_array('bbit_iso_dimension_rules', $arrDimensionVariants);
			$blnAreaVariant || $arrArea = $this->dimension_area; 
			foreach($this->arrVariantOptions['variants'] as $intID => $arrVariantData) {
				$objVariant->loadVariantData($arrVariantData);
				$this->arrDimensionData['list'][$intID] = $objVariant->dimension_list;
				$this->arrDimensionData['conversion'][$intID] = $objVariant->dimension_conversion;
				$this->arrDimensionData['rules'][$intID] = $blnRulesVariant ? $objVariant->dimension_rules : $arrRules;
				$this->arrDimensionData['area'][$intID] = $blnAreaVariant ? $objVariant->dimension_area : $arrArea;
			}
		} else {
			$this->arrDimensionData['list'][$this->id] = $this->dimension_list;
			$this->arrDimensionData['conversion'][$this->id] = $this->dimension_conversion;
			$this->arrDimensionData['rules'][$this->id] = $this->dimension_rules;
			$this->arrDimensionData['area'][$this->id] = $this->dimension_area;
		}
		
		return $this->arrDimensionData;
	}
	
	protected function injectAttribute($blnInject) {
		if($blnRemove) {
			$GLOBALS['ISO_ATTR']['bbit_iso_dimension'] = array(
				'class'		=> 'FormDimensions',
				'callback'	=> array(array('DimensionProductCallbacks', 'injectFormDimensionUnit')),
			);
		} else {
			unset($GLOBALS['ISO_ATTR']['bbit_iso_dimension']);
		}
	}
	
}

