<?php

class Dimension2DProduct extends IsotopeProduct {

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
				
			case 'is_determined_price':
				return $this->dimension_input && (!$this->arrType['variants'] || $this->arrData['pid'] != 0);
				break;
				
			case 'dimension_unit':
				return deserialize($this->arrData['bbit_iso_dimension_inputUnit'], true);
				break;
				
			case 'dimension_labels':
				$arrLabels = deserialize($this->arrType['bbit_iso_dimension_labels'], true);
				$arrLabels[0] || $arrLabels[0] = 'x';
				$arrLabels[1] || $arrLabels[1] = 'y';
				return $arrLabels;
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
				
			case 'dimension_rules':
				return deserialize($this->arrData['bbit_iso_dimension_rules'], true);
				break;

			case 'price':
				if($this->blnLocked) {
					return $this->arrData['price'];
				}
				$this->arrData['price'] || $this->arrData['price'] = $this->findDimensionPrice();
				$this->arrData['original_price'] || $this->arrData['original_price'] = $this->arrData['price'];
				
				return $this->Isotope->calculatePrice($this->arrData['price'], $this, 'price', $this->arrData['tax_class']);
				break;
				
				
			case 'tax_free_price':
				if(!$this->blnLocked) {
					$this->arrData['price'] || $this->arrData['price'] = $this->findDimensionPrice();
					$this->arrData['original_price'] || $this->arrData['original_price'] = $this->arrData['price'];
				}
				// dont break;
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
		return $arrOptions;
	}
	
	protected function generateAttribute($attribute, $varValue) {
		$arrData = $GLOBALS['TL_DCA']['tl_iso_products']['fields'][$attribute];
		
		if($arrData['eval']['rgxp'] == 'price') {
			if(!$this->is_determined_price) {
				$strReturn = sprintf($GLOBALS['TL_LANG']['MSC']['priceRangeLabel'], $this->formatted_price);
					
			} elseif($this->price <= 0) {
				$strReturn = $GLOBALS['TL_LANG']['MSC']['priceNA'];
			}
			if($strReturn) {
				return '<div class="iso_attribute" id="' . $objProduct->formSubmit . '_price">'
					. $strReturn
					. '</div>';
			}
		}
		
		return parent::generateAttribute($attribute, $varValue);
	}

	private function findDimensionPrice() {
		$arrDimensionData = $this->getDimensionData();
		if(!$arrDimensionData['maxPrice']) {
			return 0;
		}
		
		if($this->dimension_input) {
			if(!isset($this->arrDimensionPrices)) {
				try {
					$this->validateDimension($this->dimension_x, $this->dimension_y);
				} catch(Exception $e) {
				}
			}
			$fltPrice = $this->arrDimensionPrices ? reset($this->arrDimensionPrices) : 0;
			
		} else {
			$fltPrice = INF;
			
			$arrConditions = array();
			$arrPerUnitConditions = array();
			$arrMinAreas = array();
			
			foreach($arrDimensionData['maxPrice'] as $intID => $fltMaxPrice) {
				$arrConv = $arrDimensionData['conversion'][$intID];
				$strList = 'p.pid = ' . $arrDimensionData['list'][$intID];
				$arrRules = array();
				$fltMinArea = INF;
				
				if($arrDimensionData['mode'][$intID] == 'content') {
					foreach($arrDimensionData['rules'][$intID] as $arrRule) {
						$fltArea = $arrRule['area_min'] * $arrConv[0]^2;
						$fltCalcArea = $arrRule['x_min'] * $arrConv[0] * $arrRule['y_min'] * $arrConv[1];
						
						if($fltArea <= 0 && $fltCalcArea <= 0) {
							if($arrDimensionData['pricePerUnit'][$intID]) {
								// very special case, the lowest possible price is arbitrarily close to 0
								// because this rule does not force a min area
								return 0;
							}
							$arrRules = array($strList);
							break;
							
						} elseif($fltArea <= 0) {
							$fltArea = INF;
							
						} elseif($fltCalcArea <= 0) {
							$fltCalcArea = INF;
						}
						
						$arrRules[] = $strList . ' AND p.content >= ' . min($fltArea, $fltCalcArea);
						
						$fltMinArea = min($fltMinArea, $fltArea, $fltCalcArea);
					}
					
				} else {
					foreach($arrDimensionData['rules'][$intID] as $arrRule) {
						if($arrRule['x_min'] <= 0 && $arrRule['y_min'] <= 0 && $arrRule['area_min']) {
							$arrRules = array($strList);
							break;
						}
						
						$strRuleCondition = '';
						$strConjuction = '';
						
						if($arrRule['x_min'] > 0) {
							$arrRule['x_min'] *= $arrConv[0];
							$strRuleCondition .= 'p.dimension_x >= ' . $arrRule['x_min'];
							$strConjuction = ' AND ';
						}
						
						if($arrRule['y_min'] > 0) {
							$arrRule['y_min'] *= $arrConv[1];
							$strRuleCondition .= $strConjuction . 'p.dimension_y >= ' . $arrRule['y_min'];
							$strConjuction = ' AND ';
						}
						
						if($arrRule['area_min'] > 0) {
							$arrRule['area_min'] *= $arrConv[0]^2;
							$strRuleCondition .= $strConjuction . 'p.dimension_x * p.dimension_y >= ' . $arrRule['area_min'];
						}

						$arrRules[] = $strList . ' AND ' . $strRuleCondition;
						
						$fltCalcArea = $arrRule['x_min'] > 0 && $arrRule['y_min'] > 0
							? $arrRule['x_min'] * $arrRule['y_min']
							: INF;
						$fltMinArea = min($fltMinArea, $arrRule['area_min'], $fltCalcArea);
					}
				}
				
				if($arrDimensionData['pricePerUnit'][$intID]) {
					if(is_infinite($fltMinArea)) {
						// very special case, the lowest possible price is arbitrarily close to 0,
						// because there are no rules to force a min area
						return 0;
					}
					$arrMinAreas[$arrDimensionData['list'][$intID]] = $fltMinArea;
					$arrPerUnitConditions[$intID] = $arrRules ? $arrRules : array($strList);
					$fltPrice = min($fltPrice, $fltMaxPrice * $fltMinArea);
				} else {
					$arrConditions[$intID] = $arrRules ? $arrRules : array($strList);
					$fltPrice = min($fltPrice, $fltMaxPrice);
				}
			}
		
			$intTime = time();
			
			if($arrConditions) {
				$strConditions = '(' . implode(
					') OR (',
					array_unique(call_user_func_array('array_merge', $arrConditions))
				) . ')';
				
				$objPrice = $this->Database->prepare(
					'SELECT	p.price
					FROM	tl_iso_product_dimension_prices AS p
					WHERE	(' . $strConditions . ')
					AND		p.published = \'1\'
					AND		(p.start = \'\' OR p.start > ' . $intTime . ')
					AND		(p.stop = \'\' OR p.stop < ' . $intTime . ')
					ORDER BY p.price'
				)->limit(1)->execute($arrParams);
				
				$objPrice->numRows && $fltPrice = min($fltPrice, $objPrice->price);
			}
			
			if($arrPerUnitConditions) {
				$strPerUnitConditions = '(' . implode(
					') OR (',
					array_unique(call_user_func_array('array_merge', $arrPerUnitConditions))
				) . ')';
				
				$objPrice = $this->Database->prepare(
					'SELECT	p.pid, MIN(p.price) AS price
					FROM	tl_iso_product_dimension_prices AS p
					WHERE	(' . $strPerUnitConditions . ')
					AND		p.published = \'1\'
					AND		(p.start = \'\' OR p.start > ' . $intTime . ')
					AND		(p.stop = \'\' OR p.stop < ' . $intTime . ')
					GROUP BY p.pid'
				)->execute($arrParams);
				
				while($objPrice->next()) {
					$fltPrice = min($fltPrice, $objPrice->price * $arrMinAreas[$objPrice->pid]);
				}
			}
		}

		return $fltPrice;
	}
	
	public function validateDimension($fltX, $fltY) {
		if($fltX <= 0 || $fltY <= 0) {
			throw new Exception($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_positive']);
		}
		
		$intTime = time();
		
		$arrDimensionData = $this->getDimensionData();
		
		$this->arrDimensionPrices = array();
		
		$arrAllXPartMatches = array();
		$arrAllYPartMatches = array();
		
		foreach($arrDimensionData['maxPrice'] as $intID => $fltMaxPrice) {
			$arrConv = $arrDimensionData['conversion'][$intID];
			$arrRules = $arrDimensionData['rules'][$intID];
			$fltConvX = $fltX * $arrConv[0];
			$fltConvY = $fltY * $arrConv[1];
			
			if($this->matchRules($arrRules, $arrConv, $fltX, $fltY, $arrXPartMatches, $arrYPartMatches)) {
				$arrParams = array($arrDimensionData['list'][$intID]);
				
				if($arrDimensionData['mode'][$intID] == 'dimension_2d') {
					$arrParams[] = $fltConvX;
					$arrParams[] = $fltConvY;
					$strDimensionCondition = 'dimension_x >= ? AND dimension_y >= ?';
				} else {
					$arrParams[] = $fltConvX * $fltConvY;
					$strDimensionCondition = 'content >= ?';
				}
				
				$objPrice = $this->Database->prepare('
					SELECT	MIN(price) AS price
					FROM	tl_iso_product_dimension_prices
					WHERE	pid = ?
					AND		' . $strDimensionCondition . '
					AND		published = \'1\'
					AND		(start = \'\' OR start > ' . $intTime . ')
					AND		(stop = \'\' OR stop < ' . $intTime . ')
					GROUP BY pid
				')->execute($arrParams);
				
				$fltPrice = $objPrice->numRows ? $objPrice->price : $fltMaxPrice;
				$arrDimensionData['pricePerUnit'][$intID] && $fltPrice *= $fltConvX * $fltConvY;
				
				$this->arrDimensionPrices[$intListID . ',' . $fltX . ',' . $fltY] = $fltPrice;
				
			} elseif($arrXPartMatches) {
				$arrAllXPartMatches[$intID] = $arrXPartMatches;
				
			} elseif($arrYPartMatches) {
				$arrAllYPartMatches[$intID] = $arrYPartMatches;
				
			}
		}
		
		if($this->arrDimensionPrices) {
			asort($this->arrDimensionPrices);
			return;
		}
	
		if(!$arrAllXPartMatches && !$arrAllYPartMatches) {
			throw new Exception($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_noPrices']);
		}
		
		$strError = 'NOT YET IMPLEMENTED';
//		$arrUnit = $this->dimension_unit;
//		$strError = '';
//		if(isset($fltMaxX)) {
//			if($fltMinX > 0) {
//				$strError .= sprintf($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_xAdjustMinMax'], $fltY . $arrUnit[1], $fltMinX . $arrUnit[0], $fltMaxX . $arrUnit[0]);
//			} else {
//				$strError .= sprintf($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_xAdjustMax'], $fltY . $arrUnit[1], $fltMaxX . $arrUnit[0]);
//			}
//			$strConjunction = '<br />';
//		}
//		if(isset($fltMaxY)) {
//			$strError .= $strConjunction;
//			if($fltMinY > 0) {
//				$strError .= sprintf($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_yAdjustMinMax'], $fltX . $arrUnit[0], $fltMinY . $arrUnit[1], $fltMaxY . $arrUnit[1]);
//			} else {
//				$strError .= sprintf($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_yAdjustMax'], $fltX . $arrUnit[0], $fltMaxY . $arrUnit[1]);
//			}
//		}
//		if(!$strError) {
//			/*if(!$blnListMaxOK) {
//				$strError = $GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_noSizePrices'];
//				
//			} elseif(!$blnAreaOK) {
//				$strError = sprintf($GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_area'], $fltMinArea . $arrUnit[0], $fltMaxArea . $arrUnit[0]);
//				
//			} else*/ {
//				$strError = $GLOBALS['ISO_LANG']['ERR']['bbit_iso_dimension_noSizePrices'];
//				
//			}
//		}
		
		throw new Exception($strError);
	}
	
	protected $arrDimensionPrices;
	
	protected $arrDimensionData;
	
	protected function getDimensionData() {
		if(isset($this->arrDimensionData)) {
			return $this->arrDimensionData;
		}
		
		$arrDimensionVariants = array_intersect(
			array(
				'bbit_iso_dimension_rules',
				'bbit_iso_dimension_conversion',
				'bbit_iso_dimension_list'
			),
			$this->arrVariantAttributes
		);
		
		$this->arrDimensionData = array();
		
		if($this->arrType['variants'] && $this->pid == 0 && $arrDimensionVariants) {
			$objVariant = clone $this;
			$blnRulesVariant = in_array('bbit_iso_dimension_rules', $arrDimensionVariants);
			$blnRulesVariant || $arrRules = $this->dimension_rules;
			
			foreach($this->arrVariantOptions['variants'] as $intID => $arrVariantData) {
				$objVariant->loadVariantData($arrVariantData);
				$this->arrDimensionData['list'][$intID] = $objVariant->dimension_list;
				$this->arrDimensionData['conversion'][$intID] = $objVariant->dimension_conversion;
				$this->arrDimensionData['rules'][$intID] = $blnRulesVariant
					? $objVariant->dimension_rules
					: $arrRules;
			}
		} else {
			$this->arrDimensionData['list'][$this->id] = $this->dimension_list;
			$this->arrDimensionData['conversion'][$this->id] = $this->dimension_conversion;
			$this->arrDimensionData['rules'][$this->id] = $this->dimension_rules;
		}
		
		$objLists = $this->Database->query(
			'SELECT	d.id, d.mode, d.pricePerUnit,
					(SELECT MAX(p.price) FROM tl_iso_product_dimension_price AS p WHERE p.pid = d.id) AS maxPrice
			FROM	tl_iso_product_dimensions AS d
			WHERE	d.id IN (' . implode(',', array_unique($this->arrDimensionData['list'])) . ')'
		);
		
		while($objLists->next()) {
			$arrLists[$objLists->id] = $objLists->row();
		}
		
		foreach($this->arrDimensionData['list'] as $intID => $intListID) {
			$this->arrDimensionData['mode'][$intID] = $arrList[$intListID]['mode'];
			$this->arrDimensionData['pricePerUnit'][$intID] = $arrList[$intListID]['pricePerUnit'];
			$this->arrDimensionData['maxPrice'][$intID] = $arrList[$intListID]['maxPrice'];
		}
		
		asort($this->arrDimensionData['maxPrice']);
		
		return $this->arrDimensionData;
	}
	
	protected function injectAttribute($blnInject) {
		if($blnRemove) {
			$GLOBALS['ISO_ATTR']['bbit_iso_dimension_2d'] = array(
				'class'		=> 'FormDimension2D',
				'callback'	=> array(array('Dimension2DProductCallbacks', 'callbackFormDimension2D')),
			);
		} else {
			unset($GLOBALS['ISO_ATTR']['bbit_iso_dimension_2d']);
		}
	}
	
	protected function matchRules($arrRules, $arrConv, $fltX, $fltY, &$arrXPartMatches, &$arrYPartMatches) {
		$fltYToXConv = $arrConv[1] / $arrConv[0];
		$fltArea = $fltX * $fltY * $fltYToXConv;
		$arrXPartMatches = array();
		$arrYPartMatches = array();
		
		foreach($arrRules as $arrRule) {
			$arrRule['x_max'] || $arrRule['x_max'] = INF;
			$arrRule['y_max'] || $arrRule['y_max'] = INF;
			$arrRule['area_max'] || $arrRule['area_max'] = INF;
			
			// the rule can never match
			if($arrRule['x_min'] * $arrRule['y_min'] * $fltYToXConv > $arrRule['area_max']
			|| $arrRule['x_max'] * $arrRule['y_max'] * $fltYToXConv < $arrRule['area_min']) {
				continue;
			}
			
			$blnXMatch = $fltX >= $arrRule['x_min'] && $fltX <= $arrRule['x_max'];
			$blnYMatch = $fltY >= $arrRule['y_min'] && $fltY <= $arrRule['y_max'];
			$blnAreaMatch = $fltArea >= $arrRule['area_min'] && $fltArea <= $arrRule['area_max'];
			
			if($blnXMatch && $blnYMatch && $blnAreaMatch) {
				return true;
			}
			
			if($blnXMatch) {
				$arrPart['y_min'] = $fltX * $arrRule['y_min'] * $fltYToXConv < $arrRule['area_min']
					? $arrRule['area_min'] / $fltX / $fltYToXConv
					: $arrRule['y_min'];
				
				$arrPart['y_max'] = $fltX * $arrRule['y_max'] * $fltYToXConv > $arrRule['area_max']
					? $arrRule['area_max'] / $fltX / $fltYToXConv
					: $arrRule['y_max'];
				
				$arrXPartMatches[] = $arrPart;
			}
			
			if($blnYMatch) {
				$arrPart['x_min'] = $arrRule['x_min'] * $fltY * $fltYToXConv < $arrRule['area_min']
					? $arrRule['area_min'] / ($fltY * $fltYToXConv)
					: $arrRule['x_min'];
				
				$arrPart['x_max'] = $arrRule['x_max'] * $fltY * $fltYToXConv > $arrRule['area_max']
					? $arrRule['area_max'] / ($fltY * $fltYToXConv)
					: $arrRule['x_max'];
				
				$arrYPartMatches[] = $arrPart;
			}
		}
	}
	
}

