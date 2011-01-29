<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2010
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
 */


class DimensionProduct extends IsotopeProduct
{

	public function __construct($arrData, $arrOptions=null, $blnLocked=false)
	{
		$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimension_x'] = array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimension_x'],
			'inputType'				=> 'text',
			'eval'					=> array('mandatory'=>true),
			'attributes'			=> array('variant_option'=>true),
			'save_callback' => array
			(
				array('tl_iso_products_dimensions', 'validateX'),
			),
		);

		$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimension_y'] = array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimension_y'],
			'inputType'				=> 'text',
			'eval'					=> array('mandatory'=>true),
			'attributes'			=> array('variant_option'=>true),
			'save_callback' => array
			(
				array('tl_iso_products_dimensions', 'validateY'),
			),
		);
		
		$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimension_area'] = array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimension_area'],
		);
		
		// Move height & width to top of attributes
		if (count($arrOptions))
		{
			$arrDimensions = array('dimension_x' => $arrOptions['dimension_x'], 'dimension_y' => $arrOptions['dimension_y']);
			unset($arrOptions['dimension_x'], $arrOptions['dimension_y']);
			array_insert($arrOptions, 0, $arrDimensions);
		}

		parent::__construct($arrData, $arrOptions, $blnLocked);
		
		array_insert($this->arrVariantAttributes, 0, array('dimension_x', 'dimension_y', 'dimension_area'));
	}


	/**
	 * Get a property
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch( $strKey )
		{
			case 'dimension_x':
			case 'dimension_y':
				if ($this->Environment->script == 'ajax.php')
				{
					return (float)$this->Input->post($strKey);
				}
				return (float)$this->arrOptions[$strKey];
				break;
			
			case 'dimension_area':
				if ($this->dimension_x == 0 || $this->dimension_y == 0)
					return '';

				$this->loadLanguageFile('tl_product_dimensions');
				$objGroup = $this->Database->execute("SELECT * FROM tl_product_dimensions WHERE id=" . (int)$this->arrData['dimensions']);
				
				return $this->dimension_x * $this->dimension_y / 10000 . ' ' . $GLOBALS['TL_LANG']['tl_product_dimensions'][$objGroup->multiply_unit];
				break;

			case 'price':
				if ($this->blnLocked)
				{
					return $this->arrData['price'];
				}
				
				$time = time();
				$objGroup = $this->Database->execute("SELECT * FROM tl_product_dimensions WHERE id=" . (int)$this->arrData['dimensions']);
				
				$arrDimension = array('x'=>($this->arrOptions['dimension_x']*$this->quantity_requested), 'y'=>($this->arrOptions['dimension_y']*$this->quantity_requested));
				
				if ($objGroup->summarizeSize == 'product' || $objGroup->summarizeSize == 'variant' || $objGroup->summarizeSize == 'type')
				{
					foreach( $this->Isotope->Cart->getProducts() as $objProduct )
					{
						if (!($objProduct instanceof DimensionProduct) || $objProduct->cart_id == $this->cart_id)
							continue;

						switch( $objGroup->summarizeSize )
						{
							case 'product':
								if ($objProduct->id == $this->id || ($objProduct->pid > 0 && $objProduct->pid == $this->pid))
								{
									$arrDimension['x'] += ($objProduct->dimension_x * $objProduct->quantity_requested);
									$arrDimension['y'] += ($objProduct->dimension_y * $objProduct->quantity_requested);
								}
								break;
		
							case 'variant':
								if ($objProduct->id == $this->id)
								{
									$arrDimension['x'] += ($objProduct->dimension_x * $objProduct->quantity_requested);
									$arrDimension['y'] += ($objProduct->dimension_y * $objProduct->quantity_requested);
								}
								break;
		
							case 'type':
								if ($objProduct->type == $this->type)
								{
									$arrDimension['x'] += ($objProduct->dimension_x * $objProduct->quantity_requested);
									$arrDimension['y'] += ($objProduct->dimension_y * $objProduct->quantity_requested);
								}
								break;
						}
					}
				}
				
				if ($objGroup->mode == 'area')
				{
					$fltArea = $arrDimension['x'] * $arrDimension['y'];
					$objPrice = $this->Database->prepare("SELECT * FROM tl_product_dimension_prices WHERE pid=? AND area >= ? AND published='1' AND (start='' OR start>$time) AND (stop='' OR stop<$time) ORDER BY area")->limit(1)->execute($this->arrData['dimensions'], $fltArea);

					if ($objGroup->multiply_per > 0)
					{
						$intFactor = ceil(($this->arrOptions['dimension_x'] * $this->arrOptions['dimension_y']) / $objGroup->multiply_per);

						return $this->Isotope->calculatePrice(((float)$objPrice->price * $intFactor), $this, 'price', $this->arrData['tax_class']);
					}
				}
				else
				{
					$objPrice = $this->Database->prepare("SELECT * FROM tl_product_dimension_prices WHERE pid=? AND dimension_x >= ? AND dimension_y >= ? AND published='1' AND (start='' OR start>$time) AND (stop='' OR stop<$time) ORDER BY dimension_x, dimension_y")->limit(1)->execute($this->arrData['dimensions'], $arrDimension['x'], $arrDimension['y']);
				}

				return $this->Isotope->calculatePrice((float)$objPrice->price, $this, 'price', $this->arrData['tax_class']);
				break;
		}

		return parent::__get($strKey);
	}


	public function generateAjax(&$objModule)
	{
		$arrOptions = parent::generateAjax($objModule);
		
		$fltPrice = $this->price;
		
		$arrOptions[] = array('id'=>$this->formSubmit . '_price', 'html'=>('<div class="iso_attribute" id="' . $this->formSubmit . '_price">' . ($fltPrice > 0 ? $this->Isotope->formatPriceWithCurrency($fltPrice) : '') . '</div>'));
		
		return $arrOptions;
	}
}

