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
			'attributes'			=> array('is_customer_defined'=>true),
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
			'attributes'			=> array('is_customer_defined'=>true),
			'save_callback' => array
			(
				array('tl_iso_products_dimensions', 'validateY'),
			),
		);

		parent::__construct($arrData, $arrOptions, $blnLocked);
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
				return (float)$this->arrOptions[$strKey];
				break;

			case 'price':
				$time = time();
				$objGroup = $this->Database->execute("SELECT * FROM tl_product_dimensions WHERE id={$this->arrData['dimensions']}");

				if ($objGroup->mode == 'area')
				{
					$fltArea = $this->arrOptions['dimension_x'] * $this->arrOptions['dimension_y'];
					$objPrice = $this->Database->prepare("SELECT * FROM tl_product_dimension_prices WHERE pid=? AND area >= ? AND published='1' AND (start='' OR start>$time) AND (stop='' OR stop<$time) ORDER BY area")->limit(1)->execute($this->arrData['dimensions'], $fltArea);

					if ($objGroup->multiply_per > 0)
					{
						$intFactor = ceil($fltArea / $objGroup->multiply_per);
						return $this->Isotope->calculatePrice(((float)$objPrice->price * $intFactor), $this, 'price', $this->arrData['tax_class']);
					}
				}
				else
				{
					$objPrice = $this->Database->prepare("SELECT * FROM tl_product_dimension_prices WHERE pid=? AND dimension_x >= ? AND dimension_y >= ? AND published='1' AND (start='' OR start>$time) AND (stop='' OR stop<$time) ORDER BY dimension_x, dimension_y")->limit(1)->execute($this->arrData['dimensions'], $this->arrOptions['dimension_x'], $this->arrOptions['dimension_y']);
				}

				return $this->Isotope->calculatePrice((float)$objPrice->price, $this, 'price', $this->arrData['tax_class']);
				break;
		}

		return parent::__get($strKey);
	}


	/**
	 * Return all attributes for this product
	 */
	public function getAttributes()
	{
		$arrData = parent::getAttributes();

		$arrData['dimension_x'] = intval($this->arrData['dimension_x']);
		$arrData['dimension_y'] = intval($this->arrData['dimension_y']);

		return $arrData;
	}
}

