<?php

class Dimension2DProduct extends IsotopeProduct {

	public function __construct($arrData, $arrOptions=null, $blnLocked=false) {
		if($arrOptions) {
			$arrOptions = array_merge(array('bbit_iso_dimension_2d_input' => null), $arrOptions);
		}
		parent::__construct($arrData, $arrOptions, $blnLocked);
		
		$this->arrAttributes[] = 'bbit_iso_dimension_2d_input';
		$this->arrVariantAttributes[] = 'price';
	}

	public function __get($strKey) {
		switch($strKey) {
			case 'dimension_x':
				return floatval($this->arrOptions['bbit_iso_dimension_2d_input']['x']);
				break;
				
			case 'dimension_y':
				return floatval($this->arrOptions['bbit_iso_dimension_2d_input']['y']);
				break;
				
			case 'dimension_input':
				return $this->dimension_x && $this->dimension_y;
				break;
				
			case 'is_determined_price':
				return $this->dimension_input && (!$this->arrType['variants'] || $this->arrData['pid'] != 0);
				break;
				
			case 'dimension_unit':
				$strAtt = 'bbit_iso_dimension_inputUnit';
				if(!isset($this->arrCache[$strAtt])) {
					$this->arrCache[$strAtt] = deserialize($this->arrData[$strAtt], true);
				}
				return $this->arrCache[$strAtt];
				break;
				
			case 'dimension_list':
				return intval($this->arrData['bbit_iso_dimension_list']);
				break;
				
			case 'dimension_conversion':
				$strAtt = 'bbit_iso_dimension_inputConversion';
				if(!isset($this->arrCache[$strAtt])) {
					$arrConv = deserialize($this->arrData[$strAtt], true);
					$arrConv[0] = strlen($arrConv[0]) ? floatval($arrConv[0]) : 1.0;
					$arrConv[1] = strlen($arrConv[1]) ? floatval($arrConv[1]) : 1.0;
					$this->arrCache[$strAtt] = $arrConv;
				}
				return $this->arrCache[$strAtt];
				break;
				
			case 'dimension_rules':
				$strAtt = 'bbit_iso_dimension_rules';
				if(!isset($this->arrCache[$strAtt])) {
					$this->arrCache[$strAtt] = deserialize($this->arrData[$strAtt], true);
				}
				return $this->arrCache[$strAtt];
				break;
				
			case 'dimension_labels':
				$strAtt = 'bbit_iso_dimension_labels';
				if(!isset($this->arrCache[$strAtt])) {
					$arrLabels = deserialize($this->arrType[$strAtt], true);
					strlen($arrLabels[0]) || $arrLabels[0] = 'x';
					strlen($arrLabels[2]) || $arrLabels[2] = 'y';
					$this->arrCache[$strAtt] = $arrLabels;
				}
				return $this->arrCache[$strAtt];
				break;
				
			case 'price':
			case 'tax_free_price':
				$this->blnLocked || $this->findDimPrice();
				break;
		}

		return parent::__get($strKey);
	}
	
	public function getOptions($blnRaw = false) {
		if($blnRaw) {
			return parent::getOptions(true);
		}

		$arrOptions = array();
		if($this->dimension_input) {
			$arrLabels = $this->dimension_labels;
			$arrUnit = $this->dimension_unit;
			$arrOptions[] = array(
				'label' => $arrLabels[0],
				'value' => sprintf('%.0f%s', $this->dimension_x, $arrUnit[0])
			);
			$arrOptions[] = array(
				'label' => $arrLabels[2],
				'value' => sprintf('%.0f%s', $this->dimension_y, $arrUnit[1])
			);
		}
		
		$arrInput = $this->arrOptions['bbit_iso_dimension_2d_input'];
		unset($this->arrOptions['bbit_iso_dimension_2d_input']);
		
		$arrOptions = array_merge($arrOptions, parent::getOptions());
		
		if($arrInput) {
			$this->arrOptions['bbit_iso_dimension_2d_input'] = $arrInput;
		}

		return $arrOptions;
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
				return '<div class="iso_attribute" id="' . $this->formSubmit . '_price">'
					. $strReturn
					. '</div>';
			}
		}
		
		return parent::generateAttribute($attribute, $varValue);
	}

	private $strDimPriceKey;
	
	private function findDimPrice($blnCache = true) {
		$arrDimData = $this->getDimData();
		
		$strDimPriceKey = $this->dimension_x . ',' . $this->dimension_y;
		if($blnCache && isset($this->arrData['price']) && $strDimPriceKey == $this->strDimPriceKey) {
			return;
		}
		$this->strDimPriceKey = $strDimPriceKey;
		
		if(!$arrDimData['maxPrice']) {
			$fltPrice = 0;
			
		} elseif($this->dimension_input) {
			if(!isset($this->arrDimPrices)) {
				try {
					$this->validateDim($this->dimension_x, $this->dimension_y);
				} catch(Exception $e) {
				}
			}
			$fltPrice = $this->arrDimPrices ? reset($this->arrDimPrices) : 0;
			
		} else {
			$fltPrice = INF;
			
			$arrConditions = array();
			$arrPerUnitConditions = array();
			$arrMinAreas = array();
			
			foreach($arrDimData['maxPrice'] as $intID => $fltMaxPrice) {
				$arrConv = $arrDimData['conversion'][$intID];
				$strList = 'p.pid = ' . $arrDimData['list'][$intID];
				$arrRules = array();
				$fltMinArea = INF;
				
				if($arrDimData['listMode'][$intID] == 'content') {
					foreach($arrDimData['rules'][$intID] as $arrRule) {
						$fltArea = $arrRule['area_min'] * $arrConv[0]^2;
						$fltCalcArea = $arrRule['x_min'] * $arrConv[0] * $arrRule['y_min'] * $arrConv[1];
						
						if($fltArea <= 0 && $fltCalcArea <= 0) {
							if($arrDimData['pricePerUnit'][$intID]) {
								// very special case, the lowest possible price is arbitrarily close to 0
								// because this rule does not force a min area
								unset($arrConditions, $arrPerUnitConditions);
								$fltPrice = 0;
								break 2;
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
					foreach($arrDimData['rules'][$intID] as $arrRule) {
						if($arrRule['x_min'] <= 0 && $arrRule['y_min'] <= 0 && $arrRule['area_min'] <= 0) {
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
				
				if($arrDimData['pricePerUnit'][$intID]) {
					if(is_infinite($fltMinArea)) {
						// very special case, the lowest possible price is arbitrarily close to 0,
						// because there are no rules to force a min area
						unset($arrConditions, $arrPerUnitConditions);
						$fltPrice = 0;
						break;
					}
					$arrMinAreas[$arrDimData['list'][$intID]] = $fltMinArea;
					$arrPerUnitConditions[$intID] = $arrRules ? $arrRules : array($strList);
					$fltPrice = min($fltPrice, $fltMaxPrice * $fltMinArea);
				} else {
					$arrConditions[$intID] = $arrRules ? $arrRules : array($strList);
					$fltPrice = min($fltPrice, $fltMaxPrice);
				}
			}
			
			$intTime = time();
			
			if($arrConditions) {
				$arrConditions = call_user_func_array('array_merge', $arrConditions);
				$strConditions = '(' . implode(') OR (', array_unique($arrConditions)) . ')';
				
				$objPrice = $this->Database->prepare(
					'SELECT	p.price
					FROM	tl_bbit_iso_dimension_price AS p
					WHERE	(' . $strConditions . ')
					AND		p.published = \'1\'
					AND		(p.start = \'\' OR p.start > ' . $intTime . ')
					AND		(p.stop = \'\' OR p.stop < ' . $intTime . ')
					ORDER BY p.price'
				)->limit(1)->execute($arrParams);
				
				$objPrice->numRows && $fltPrice = min($fltPrice, $objPrice->price);
			}
			
			if($arrPerUnitConditions) {
				$arrPerUnitConditions = call_user_func_array('array_merge', $arrPerUnitConditions);
				$strPerUnitConditions = '(' . implode(') OR (', array_unique($arrPerUnitConditions)) . ')';
				
				$objPrice = $this->Database->prepare(
					'SELECT	p.pid, MIN(p.price) AS price
					FROM	tl_bbit_iso_dimension_price AS p
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
		
		$this->arrData['price'] = $fltPrice;
		$this->arrData['original_price'] = $fltPrice;
	}
	
	protected $arrDimPrices;
	
	public function validateDim($fltX, $fltY) {
		if($fltX <= 0 || $fltY <= 0) {
			throw new Exception($GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_positive']);
		}
		
		$intTime			= time();
		$arrDimData			= $this->getDimData();
		$this->arrDimPrices	= array();
		$arrXMatchMin		= array();
		$arrXMatchMax		= array();
		$arrYMatchMin		= array();
		$arrYMatchMax		= array();
		
		foreach($arrDimData['maxPrice'] as $intID => $fltMaxPrice) {
			$arrConv	= $arrDimData['conversion'][$intID];
			$arrRules	= $arrDimData['rules'][$intID];
			$fltConvX	= $fltX * $arrConv[0];
			$fltConvY	= $fltY * $arrConv[1];
			
			if($this->matchRules($arrRules, $arrConv, $fltX, $fltY, $arrXPartMatches, $arrYPartMatches)) {
				$arrParams = array($arrDimData['list'][$intID]);
				
				if($arrDimData['listMode'][$intID] == 'dimension_2d') {
					$arrParams[]	= $fltConvX;
					$arrParams[]	= $fltConvY;
					$strCondition	= 'dimension_x >= ? AND dimension_y >= ?';
				} else {
					$arrParams[]	= $fltConvX * $fltConvY;
					$strCondition	= 'content >= ?';
				}
				
				$objPrice = $this->Database->prepare('
					SELECT	MIN(price) AS price
					FROM	tl_bbit_iso_dimension_price
					WHERE	pid = ?
					AND		' . $strCondition . '
					AND		published = \'1\'
					AND		(start = \'\' OR start > ' . $intTime . ')
					AND		(stop = \'\' OR stop < ' . $intTime . ')
					GROUP BY pid
				')->execute($arrParams);
				
				$fltPrice = $objPrice->numRows ? $objPrice->price : $fltMaxPrice;
				$arrDimData['pricePerUnit'][$intID] && $fltPrice *= $fltConvX * $fltConvY;
				
				$strKey = $arrDimData['list'][$intID] . ',' . $fltX . ',' . $fltY;
				if(!isset($this->arrDimPrices[$strKey]) || $this->arrDimPrices[$strKey] > $fltPrice) {
					$this->arrDimPrices[$strKey] = $fltPrice;
				}
				
			} else {
				if($arrXPartMatches) foreach($arrXPartMatches as $arrMatch) {
					$arrXMatchMin[] = $arrMatch[0];
					$arrXMatchMax[] = $arrMatch[1];
				}
				if($arrYPartMatches) foreach($arrYPartMatches as $arrMatch) {
					$arrYMatchMin[] = $arrMatch[0];
					$arrYMatchMax[] = $arrMatch[1];
				}
			}
		}
		
		if($this->arrDimPrices) {
			asort($this->arrDimPrices);
//			var_dump($this->arrDimPrices);
			return;
		}
	
//		var_dump($arrXMatchMin, $arrXMatchMax, $arrYMatchMin, $arrYMatchMax);
		
		if(!$arrXMatchMin && !$arrYMatchMin) {
			throw new Exception($GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_errNoPrices']);
		}
		
		$arrUnit	= $this->dimension_unit;
		$arrLabels	= $this->dimension_labels;
		$strRanges	= $GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_errRanges'];
		$strMinMax	= $GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_errMinMax'];
		$strMax		= $GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_errMax'];
		$strMin		= $GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_errMin'];
		
		if($arrXMatchMin) {
			array_multisort($arrXMatchMin, SORT_NUMERIC, SORT_ASC, $arrXMatchMax, SORT_NUMERIC, SORT_DESC);
			
			// join overlapping ranges
			$arrRanges = array();
			$arrRange = array($arrXMatchMin[0], $arrXMatchMax[0]);
			for($i = 1, $n = count($arrXMatchMin); $i < $n; $i++) {
				if($arrRange[1] < $arrXMatchMin[$i]) { // hole
					$arrRanges[] = $arrRange;
					$arrRange = array($arrXMatchMin[$i], $arrXMatchMax[$i]);
					
				} elseif($arrRange[1] < $arrXMatchMax[$i]) { // new max
					$arrRange[1] = $arrXMatchMax[$i];
				}
				
				while($arrXMatchMin[$i + 1] == $arrRange[0]) $i++;
			}
			$arrRanges[] = $arrRange;
			
			foreach($arrRanges as &$arrRange) {
				if($arrRange[0] == 0) {
					$arrRange = sprintf($strMax, $arrRange[1], $arrUnit[1]);
					
				} elseif(is_infinite($arrRange[1])) {
					$arrRange = sprintf($strMin, $arrRange[0], $arrUnit[1]);
					
				} else {
					$arrRange = sprintf($strMinMax, $arrRange[0], $arrUnit[1], $arrRange[1], $arrUnit[1]);
				}
			}
			$strError = sprintf($strRanges,
				$arrLabels[2],
				$fltX,
				$arrUnit[0],
				$arrLabels[0],
				implode(', ', $arrRanges)
			);
			$strConjunction = '<br />';
		}
		
		if($arrYMatchMin) {
			array_multisort($arrYMatchMin, SORT_NUMERIC, SORT_ASC, $arrYMatchMax, SORT_NUMERIC, SORT_DESC);
			
			// join overlapping ranges
			$arrRanges = array();
			$arrRange = array($arrYMatchMin[0], $arrYMatchMax[0]);
			for($i = 1, $n = count($arrYMatchMin); $i < $n; $i++) {
				if($arrRange[1] < $arrYMatchMin[$i]) { // hole
					$arrRanges[] = $arrRange;
					$arrRange = array($arrYMatchMin[$i], $arrYMatchMax[$i]);
					
				} elseif($arrRange[1] < $arrYMatchMax[$i]) { // new max
					$arrRange[1] = $arrYMatchMax[$i];
				}
				
				while($arrYMatchMin[$i + 1] == $arrRange[0]) $i++;
			}
			$arrRanges[] = $arrRange;
			
			foreach($arrRanges as &$arrRange) {
				if($arrRange[0] == 0) {
					$arrRange = sprintf($strMax, $arrRange[1], $arrUnit[0]);
					
				} elseif(is_infinite($arrRange[1])) {
					$arrRange = sprintf($strMin, $arrRange[0], $arrUnit[0]);
					
				} else {
					$arrRange = sprintf($strMinMax, $arrRange[0], $arrUnit[0], $arrRange[1], $arrUnit[0]);
				}
			}
			$strError .= $strConjunction . sprintf($strRanges,
				$arrLabels[0],
				$fltY,
				$arrUnit[1],
				$arrLabels[2],
				implode(', ', $arrRanges)
			);
		}
		
		throw new Exception($strError);
	}
	
	protected $arrDimData;
	
	protected function getDimData() {
		if(isset($this->arrDimData)) {
			return $this->arrDimData;
		}
		
		$this->arrDimData = array();
		
		$arrDimVariant = array_intersect_key(
			array(
				'bbit_iso_dimension_list' => true,
				'bbit_iso_dimension_inputConversion' => true,
				'bbit_iso_dimension_rules' => true,
			),
			array_flip($this->arrVariantAttributes)
		);
		
		if($this->arrType['variants'] && $this->pid == 0 && $arrDimVariant && count($this->arrVariantOptions['variants'])) {
			$objVariant = clone $this;
			foreach($this->arrVariantOptions['variants'] as $intID => $arrVariantData) {
				$objVariant->loadVariantData($arrVariantData);
				$arrInherit = array_flip(deserialize($arrVariantData['inherit'], true));
			
				if(isset($arrDimVariant['bbit_iso_dimension_list'])
				&& !isset($arrInherit['bbit_iso_dimension_list'])) {
					$this->arrDimData['list'][$intID] = $objVariant->dimension_list;
				} else {
					$this->arrDimData['list'][$intID] = $this->dimension_list;
				}
				
				if(isset($arrDimVariant['bbit_iso_dimension_inputConversion'])
				&& !isset($arrInherit['bbit_iso_dimension_inputConversion'])) {
					$this->arrDimData['conversion'][$intID] = $objVariant->dimension_conversion;
				} else {
					$this->arrDimData['conversion'][$intID] = $this->dimension_conversion;
				}
				
				if(isset($arrDimVariant['bbit_iso_dimension_rules'])
				&& !isset($arrInherit['bbit_iso_dimension_rules'])) {
					$this->arrDimData['rules'][$intID] = $objVariant->dimension_rules;
				} else {
					$this->arrDimData['rules'][$intID] = $this->dimension_rules;
				}
			}
		} else {
			$this->arrDimData['list'][$this->id] = $this->dimension_list;
			$this->arrDimData['conversion'][$this->id] = $this->dimension_conversion;
			$this->arrDimData['rules'][$this->id] = $this->dimension_rules;
		}
		
		$objLists = $this->Database->query(
			'SELECT	d.id, d.mode, d.pricePerUnit,
					(SELECT MAX(p.price) FROM tl_bbit_iso_dimension_price AS p WHERE p.pid = d.id) AS maxPrice
			FROM	tl_bbit_iso_dimension AS d
			WHERE	d.id IN (' . implode(',', array_unique($this->arrDimData['list'])) . ')'
		);
		
		while($objLists->next()) {
			$arrLists[$objLists->id] = $objLists->row();
		}
		
		foreach($this->arrDimData['list'] as $intID => $intListID) {
			$this->arrDimData['listMode'][$intID] = $arrLists[$intListID]['mode'];
			$this->arrDimData['pricePerUnit'][$intID] = $arrLists[$intListID]['pricePerUnit'];
			$this->arrDimData['maxPrice'][$intID] = $arrLists[$intListID]['maxPrice'];
		}
		
		$this->arrDimData['maxPrice'] = array_filter($this->arrDimData['maxPrice'], 'is_scalar');
		asort($this->arrDimData['maxPrice']);
		
		return $this->arrDimData;
	}
	
	protected function injectAttribute($blnInject) {
		if($blnInject) {
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
				$arrPart = array();
				$arrPart[] = $fltX * $arrRule['y_min'] * $fltYToXConv < $arrRule['area_min']
					? $arrRule['area_min'] / $fltX / $fltYToXConv
					: $arrRule['y_min'];
				
				$arrPart[] = $fltX * $arrRule['y_max'] * $fltYToXConv > $arrRule['area_max']
					? $arrRule['area_max'] / $fltX / $fltYToXConv
					: $arrRule['y_max'];
				
				$arrXPartMatches[] = $arrPart;
			}
			
			if($blnYMatch) {
				$arrPart = array();
				$arrPart[] = $arrRule['x_min'] * $fltY * $fltYToXConv < $arrRule['area_min']
					? $arrRule['area_min'] / ($fltY * $fltYToXConv)
					: $arrRule['x_min'];
				
				$arrPart[] = $arrRule['x_max'] * $fltY * $fltYToXConv > $arrRule['area_max']
					? $arrRule['area_max'] / ($fltY * $fltYToXConv)
					: $arrRule['x_max'];
				
				$arrYPartMatches[] = $arrPart;
			}
		}
	}
	
}

