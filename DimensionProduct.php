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

	/**
	 * Name of the Javascript class
	 */
	protected $ajaxClass = 'IsotopeDimensionProduct';


	public function __construct($arrData, $arrOptions=null, $blnLocked=false)
	{
		// Get the unit to stick it to the label
		$this->import('Database');
		$unit = $GLOBALS['TL_LANG']['tl_iso_products'][$this->Database->query("SELECT `unit` FROM tl_iso_product_dimensions WHERE id=" . (int)$arrData['dimensions'])->unit.'_label'];

		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/isotope_dimensions/html/dimensionproduct.js';

		$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimension_x'] = array
		(
			'label'					=> $GLOBALS['TL_LANG']['tl_iso_products']['dimension_x'][0] . ' ('.$unit.')',
			'inputType'				=> 'text',
			'eval'					=> array('mandatory'=>true, 'class'=>(($arrData['dimensions_constrain'] == 'constrain_x') && ($arrData['dimensions_ratio'] > 0) ? 'constrained' : '')),
			'attributes'			=> array('variant_option'=>true),
			'save_callback'			=> array
			(
				array('tl_iso_products_dimensions', 'validateX'),
			),
		);

		$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimension_y'] = array
		(
			'label'					=> $GLOBALS['TL_LANG']['tl_iso_products']['dimension_y'][0] . ' ('.$unit.')',
			'inputType'				=> 'text',
			'eval'					=> array('mandatory'=>true, 'class'=>(($arrData['dimensions_constrain'] == 'constrain_y') && ($arrData['dimensions_ratio'] > 0) ? 'constrained' : '')),
			'attributes'			=> array('variant_option'=>true),
			'save_callback'			=> array
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

		if  (($arrData['dimensions_constrain'] == 'constrain_y') && ($arrData['dimensions_ratio'] > 0))
		{
			array_insert($this->arrVariantAttributes, 0, array('dimension_x', 'dimension_y', 'dimension_area'));
		}
		else
		{
			array_insert($this->arrVariantAttributes, 0, array('dimension_y', 'dimension_x', 'dimension_area'));
		}
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
					$dimension = (float)$this->Input->post($strKey);
				}
				else
				{
					$dimension = (float)$this->arrOptions[$strKey];
					if (strpos($strKey, 'x') !== false)
					{
						$dimensions_max = (int)$this->dimensions_max[0];
						$dimensions_min = (int)$this->dimensions_min[0];
					}
					else
					{
						$dimensions_max = (int)$this->dimensions_max[1];
						$dimensions_min = (int)$this->dimensions_min[1];
					}

					if ($dimensions_max > 0)
					{
						$dimension = max(0, min(max($dimension, $dimensions_min), $dimensions_max));
					}
					else
					{
						$dimension = max(0, max($dimension, $this->dimensions_min[0]));
					}
				}

				return $dimension;
				break;

			case 'dimension_area':
				if ($this->dimension_x == 0 || $this->dimension_y == 0)
					return '';

				$this->loadLanguageFile('tl_iso_product_dimensions');
				$objGroup = $this->Database->execute("SELECT * FROM tl_iso_product_dimensions WHERE id=" . (int)$this->arrData['dimensions']);

				return $this->dimension_x * $this->dimension_y / 10000 . ' ' . $GLOBALS['TL_LANG']['tl_iso_product_dimensions'][$objGroup->multiply_unit];
				break;

			case 'price':
				return $this->blnLocked ? $this->arrData['price'] : $this->Isotope->calculatePrice($this->findDimensionPrice(), $this, 'price', $this->arrData['tax_class']);
				break;

			case 'min_price':
				return $this->blnLocked ? $this->arrData['price'] : $this->Isotope->calculatePrice($this->findDimensionPrice(true), $this, 'price', $this->arrData['tax_class']);
				break;

			case 'formatted_min_price':
				return $this->Isotope->formatPriceWithCurrency($this->min_price);
				break;

			case 'tax_free_price':
				return $this->blnLocked ? $this->arrData['price'] : $this->Isotope->calculatePrice($this->findDimensionPrice(), $this, 'price');
				break;
		}

		return parent::__get($strKey);
	}


	public function generateAjax(&$objModule)
	{
		if (($this->dimensions_ratio > 0) && $this->dimensions_constrain)
		{
			if ($this->dimensions_constrain == 'constrain_x')
			{
				$this->Input->setPost('dimension_x', (int)($this->dimension_y * $this->dimensions_ratio));
			}
			else
			{
				$this->Input->setPost('dimension_y', (int)($this->dimension_x * $this->dimensions_ratio));
			}
		}

		$arrOptions = parent::generateAjax($objModule);

		$fltPrice = $this->price;

		$arrOptions[] = array('id'=>$this->formSubmit . '_price', 'html'=>('<div class="iso_attribute" id="' . $this->formSubmit . '_price">' . ($fltPrice > 0 ? $this->Isotope->formatPriceWithCurrency($fltPrice) : '') . '</div>'));

		return $arrOptions;
	}


	private function findDimensionPrice($minPrice = false)
	{
		$time = time();
		$objGroup = $this->Database->execute("SELECT * FROM tl_iso_product_dimensions WHERE id=" . (int)$this->arrData['dimensions']);

		if ($objGroup->mode == 'area')
		{
			$fltArea = $minPrice
					 ? ($this->dimension_x * $this->dimension_y) * $this->quantity_requested
					 : ($this->arrOptions['dimension_x'] * $this->arrOptions['dimension_y']) * $this->quantity_requested;

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
								$fltArea += (($objProduct->dimension_x * $objProduct->dimension_y) * $objProduct->quantity_requested);
							}
							break;

						case 'variant':
							if ($objProduct->id == $this->id)
							{
								$fltArea += (($objProduct->dimension_x * $objProduct->dimension_y) * $objProduct->quantity_requested);
							}
							break;

						case 'type':
							if ($objProduct->type == $this->type)
							{
								$fltArea += (($objProduct->dimension_x * $objProduct->dimension_y) * $objProduct->quantity_requested);
							}
							break;
					}
				}
			}

			$objPrice = $this->Database->prepare("SELECT * FROM tl_iso_product_dimension_prices WHERE pid=? AND area >= ? AND published='1' AND (start='' OR start>$time) AND (stop='' OR stop<$time) ORDER BY area")->limit(1)->execute($this->arrData['dimensions'], $fltArea);

			if ($objGroup->multiply_per > 0)
			{
				$intFactor = $minPrice
						   ? ($this->dimension_x * $this->dimension_y) / $objGroup->multiply_per
						   : ($this->arrOptions['dimension_x'] * $this->arrOptions['dimension_y']) / $objGroup->multiply_per;
				return ((float)$objPrice->price * $intFactor);
			}
		}
		else
		{
			$arrDimension = array('x'=>($this->arrOptions['dimension_x']*$this->quantity_requested), 'y'=>($this->arrOptions['dimension_y']*$this->quantity_requested));
//echo 'arrDimension: ' . print_r($this->arrData, true);
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
//echo "SELECT * FROM tl_iso_product_dimension_prices WHERE pid={$this->arrData['dimensions']} AND dimension_x >= {$arrDimension['x']} AND dimension_y >= {$arrDimension['y']} AND published='1' AND (start='' OR start>{$time}) AND (stop='' OR stop<{$time}) ORDER BY dimension_x, dimension_y";
			$objPrice = $this->Database->prepare("SELECT * FROM tl_iso_product_dimension_prices WHERE pid=? AND dimension_x >= ? AND dimension_y >= ? AND published='1' AND (start='' OR start>$time) AND (stop='' OR stop<$time) ORDER BY dimension_x, dimension_y")->limit(1)->execute($this->arrData['dimensions'], $arrDimension['x'], $arrDimension['y']);
		}

		return $objPrice->price;
	}
}

