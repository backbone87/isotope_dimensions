<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright © 2005-2009 Leo Feyer
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


/**
 * Table tl_iso_product_dimensions
 */
$GLOBALS['TL_DCA']['tl_iso_product_dimensions'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'					=> 'Table',
		'enableVersioning'				=> true,
		'ctable'						=> array('tl_iso_product_dimension_prices'),
		'switchToEdit'					=> true,
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'						=> 1,
			'fields'					=> array('name'),
			'flag'						=> 1,
			'panelLayout'				=> 'filter;search,limit',
		),
		'label' => array
		(
			'fields'					=> array('name'),
			'format'					=> '%s',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'					=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'					=> 'act=select',
				'class'					=> 'header_edit_all',
				'attributes'			=> 'onclick="Backend.getScrollOffset();"',
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'					=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['edit'],
				'href'					=> 'table=tl_iso_product_dimension_prices',
				'icon'					=> 'edit.gif',
			),
			'copy' => array
			(
				'label'					=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['copy'],
				'href'					=> 'act=copy',
				'icon'					=> 'copy.gif',
			),
			'delete' => array
			(
				'label'					=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['delete'],
				'href'					=> 'act=delete',
				'icon'					=> 'delete.gif',
				'attributes'			=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show' => array
			(
				'label'					=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['show'],
				'href'					=> 'act=show',
				'icon'					=> 'show.gif',
			),/*

			'load' => array
			(
				'label'					=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['load'],
				'href'					=> 'key=load',
				'icon'					=> 'system/modules/isotope_dimensions/html/load-table.png',
			),
*/
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'						=> '{name_legend},name;{config_legend},mode,multiply_per,unit,multiply_unit;{price_legend},summarizeSize',
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['name'],
			'inputType'					=> 'text',
			'eval'						=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
		),
		'mode' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['mode'],
			'filter'					=> true,
			'inputType'					=> 'radio',
			'default'					=> 'dimensions',
			'options'					=> array('dimensions', 'area'),
			'reference'					=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions'],
			'eval'						=> array('mandatory'=>true, 'tl_class'=>'clr w50'),
		),
		'multiply_per' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['multiply_per'],
			'filter'					=> true,
			'inputType'					=> 'text',
			'eval'						=> array('rgxp'=>'digit', 'maxlength'=>21, 'tl_class'=>'w50'),
		),
		'multiply_unit' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['multiply_unit'],
			'filter'					=> true,
			'inputType'					=> 'radio',
			'default'					=> 'dimensions',
			'options'					=> array('qmm', 'qcm', 'qm', 'qkm'),
			'reference'					=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions'],
			'eval'						=> array('mandatory'=>true, 'tl_class'=>'w50 w50h'),
		),
		'unit' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['unit'],
			'filter'					=> true,
			'inputType'					=> 'radio',
			'default'					=> 'dimensions',
			'options'					=> array('mm', 'cm', 'm', 'km'),
			'reference'					=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions'],
			'eval'						=> array('mandatory'=>true, 'tl_class'=>'w50 w50h'),
		),
		'summarizeSize' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['summarizeSize'],
			'filter'					=> true,
			'inputType'					=> 'radio',
			'default'					=> 'item',
			'options'					=> array('item', 'product', 'variant', 'type'),
			'reference'					=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions'],
			'eval'						=> array('mandatory'=>true, 'tl_class'=>'clr'),
		),
	)
);

