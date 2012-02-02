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



$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimension_x'] = array
(
	'label'					=> $GLOBALS['TL_LANG']['tl_iso_products']['dimension_x'][0] . ' (' . $this->objDimension->unit . ')',
	'inputType'				=> 'text',
	'eval'					=> array('mandatory'=>true, 'class'=>(($arrData['dimensions_constrain'] == 'constrain_x') && ($arrData['dimensions_ratio'] > 0) ? 'constrained' : '')),
	'attributes'			=> array('variant_option'=>true),
	'save_callback'			=> array(
		array('DimensionProductDCA', 'validateX'),
	),
);

$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimension_y'] = array
(
	'label'					=> $GLOBALS['TL_LANG']['tl_iso_products']['dimension_y'][0],
	'inputType'				=> 'text',
	'eval'					=> array('mandatory'=>true, 'class'=>(($arrData['dimensions_constrain'] == 'constrain_y') && ($arrData['dimensions_ratio'] > 0) ? 'constrained' : '')),
	'attributes'			=> array('variant_option'=>true),
	'save_callback'			=> array(
		array('DimensionProductDCA', 'validateY'),
	),
);
		
$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimensions'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimensions'],
	'inputType'				=> 'select',
	'foreignKey'			=> 'tl_iso_product_dimensions.name',
	'eval'					=> array('mandatory'=>true, 'includeBlankOption'=>true, 'tl_class'=>'clr'),
	'attributes'			=> array('legend'=>'pricing_legend'),
);

$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimensions_min'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_min'],
	'inputType'				=> 'text',
	'eval'					=> array('mandatory'=>true, 'multiple'=>true, 'size'=>2, 'rgxp'=>'digits', 'tl_class'=>'w50'),
	'attributes'			=> array('legend'=>'pricing_legend'),
);

$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimensions_max'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_max'],
	'inputType'				=> 'text',
	'eval'					=> array('mandatory'=>true, 'multiple'=>true, 'size'=>2, 'rgxp'=>'digits', 'tl_class'=>'w50'),
	'attributes'			=> array('legend'=>'pricing_legend'),
);

$GLOBALS['TL_DCA']['tl_iso_products']['fields']['area_min'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_iso_products']['area_min'],
	'inputType'				=> 'text',
	'eval'					=> array('mandatory'=>true, 'rgxp'=>'digits', 'tl_class'=>'w50'),
	'attributes'			=> array('legend'=>'pricing_legend'),
);

$GLOBALS['TL_DCA']['tl_iso_products']['fields']['area_max'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_iso_products']['area_max'],
	'inputType'				=> 'text',
	'eval'					=> array('mandatory'=>true, 'rgxp'=>'digits', 'tl_class'=>'w50'),
	'attributes'			=> array('legend'=>'pricing_legend'),
);

$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimensions_rules'] = array(
	'label'			=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_rules'],
	'exclude' 		=> true,
	'inputType' 	=> 'multiColumnWizard',
	'eval' 			=> array(
		'columnFields' => array(
			'dimensions_x_min' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_x_min'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array('rgxp' => 'digit', 'maxlength' => 21, 'style' => 'width:100px')
			),
			'dimensions_x_max' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_x_max'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array('rgxp' => 'digit', 'maxlength' => 21, 'style' => 'width:100px')
			),
			'dimensions_y_min' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_y_min'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array('rgxp' => 'digit', 'maxlength' => 21, 'style' => 'width:100px')
			),
			'dimensions_y_max' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_y_max'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array('rgxp' => 'digit', 'maxlength' => 21, 'style' => 'width:100px')
			),
		)
	)
);

$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimensions_ratio'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_ratio'],
	'inputType'				=> 'text',
	'eval'					=> array('mandatory'=>true, 'rgxp'=>'digits', 'tl_class'=>'w50'),
	'attributes'			=> array('legend'=>'pricing_legend'),
	'save_callback'			=> array
	(
		array('tl_iso_products_dimensions', 'generateRatio')
	),
);

$GLOBALS['TL_DCA']['tl_iso_products']['fields']['dimensions_constrain'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_constrain'],
	'inputType'				=> 'select',
	'options'				=> array('constrain_x', 'constrain_y'),
	'reference'				=> &$GLOBALS['TL_LANG']['tl_iso_products'],
	'eval'					=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'attributes'			=> array('legend'=>'pricing_legend'),
	'save_callback'			=> array
	(
		array('tl_iso_products_dimensions', 'setConstrain')
	),
);

class tl_iso_products_dimensions extends Controller
{

	public function validateX($varValue, $objProduct)
	{
		if (TL_MODE == 'FE' && $objProduct instanceof DimensionProduct)
		{
			if (is_array($objProduct->dimensions_min) && $objProduct->dimensions_min[0] > $varValue)
			{
				throw new Exception(sprintf($GLOBALS['ISO_LANG']['ERR']['dimensionMinWidth'], $objProduct->dimensions_min[0]));
			}
			elseif (is_array($objProduct->dimensions_max) && $objProduct->dimensions_max[0] < $varValue)
			{
				throw new Exception(sprintf($GLOBALS['ISO_LANG']['ERR']['dimensionMaxWidth'], $objProduct->dimensions_max[0]));
			}
		}

		return $varValue;
	}

	public function validateY($varValue, $objProduct)
	{
		if (TL_MODE == 'FE' && $objProduct instanceof DimensionProduct)
		{
			if (is_array($objProduct->dimensions_min) && $objProduct->dimensions_min[1] > $varValue)
			{
				throw new Exception(sprintf($GLOBALS['ISO_LANG']['ERR']['dimensionMinHeight'], $objProduct->dimensions_min[1]));
			}
			elseif (is_array($objProduct->dimensions_max) && $objProduct->dimensions_max[1] < $varValue)
			{
				throw new Exception(sprintf($GLOBALS['ISO_LANG']['ERR']['dimensionMaxHeight'], $objProduct->dimensions_max[1]));
			}
		}

		return $varValue;
	}


	/**
	 * Auto-generate the ratio and dimension to constrain.
	 * Instead of a number enter #, #x or #y as ratio. This function will calculate the ratio.
	 */
	public function generateRatio($varValue, DataContainer $dc)
	{
		if (!preg_match('/#([XYxy]?)/', str_replace('&#35;', '#', $varValue), $matches))
		{
			return $varValue;
		}

		$image = null;
		$images = deserialize($dc->activeRecord->images);
		if (is_array($images) && count($images) > 0) {
			$image = $images[0];
		}

		$strFile = $image ? 'isotope/' . strtolower(substr($image['src'], 0, 1)) . '/' . $image['src'] : null;
		if ($strFile && is_file(TL_ROOT . '/' . $strFile))
		{
			$objImage = Image::createFromFile($strFile);

			switch (strtolower($matches[1]))
			{
				case 'y':
					$varValue = (float)($objImage->height / $objImage->width);
					break;

				default:
					$varValue = (float)($objImage->width / $objImage->height);
					break;
			}
		}

		return $varValue;
	}


	/**
	 * Set the constrain dimension value.
	 * Instead of a number enter #, #x or #y as ratio. This function will constrain a dimension.
	 */
	public function setConstrain($varValue, DataContainer $dc)
	{
		if (!preg_match('/#([XYxy])/', str_replace('&#35;', '#', $this->Input->post('dimensions_ratio')), $matches))
		{
			return $varValue;
		}

		return 'constrain_' . strtolower($matches[1]);
	}
}

