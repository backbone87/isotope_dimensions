<?php

$GLOBALS['TL_DCA']['tl_iso_product_dimension_prices'] = array(

	'config' => array(
		'dataContainer'		=> 'Table',
		'enableVersioning'	=> true,
		'ptable'			=> 'tl_iso_product_dimensions',
		'onload_callback'	=> array(
			array('tl_iso_product_dimension_prices', 'selectPalette'),
		),
	),

	'list' => array(
		'sorting' => array(
			'mode'					=> 4,
			'fields'				=> array('dimension_x', 'dimension_y'),
			'flag'					=> 1,
			'panelLayout'			=> 'filter;search,limit',
			'headerFields'			=> array('name', 'mode', 'unit'),
			'disableGrouping'		=> true,
			'child_record_callback'	=> array('DimensionProductCallbacks', 'listPrice')
		),
		'global_operations' => array(
			'all' => array(
				'label'		=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'		=> 'act=select',
				'class'		=> 'header_edit_all',
				'attributes'=> 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array(
			'edit' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['edit'],
				'href'	=> 'act=edit',
				'icon'	=> 'edit.gif'
			),
			'copy' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['copy'],
				'href'	=> 'act=copy',
				'icon'	=> 'copy.gif'
			),
			'delete' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['delete'],
				'href'	=> 'act=delete',
				'icon'	=> 'delete.gif',
				'attributes'			=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['show'],
				'href'	=> 'act=show',
				'icon'	=> 'show.gif'
			),
		)
	),

	'palettes' => array(
		'dimension_2d'	=> '{dimension_legend},dimension_x,dimension_y;'
			. '{price_legend},price;'
			. '{publish_legend},published,start,stop',
			
		'area'			=> '{dimension_legend},area;'
			. '{price_legend},price;'
			. '{publish_legend},published,start,stop',
	),

	'fields' => array(
		'dimension_x' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['dimension_x'],
			'exclude'	=> true,
			'filter'	=> true,
			'inputType'	=> 'text',
			'eval'		=> array(
				'mandatory'	=> true,
				'maxlength'	=> 21,
				'rgxp'		=> 'digit',
				'tl_class'	=> 'w50'
			),
		),
		'dimension_y' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['dimension_y'],
			'exclude'	=> true,
			'filter'	=> true,
			'inputType'	=> 'text',
			'eval'		=> array(
				'mandatory'	=> true,
				'maxlength'	=> 21,
				'rgxp'		=> 'digit',
				'tl_class'	=> 'w50'
			),
		),
		'area' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['area'],
			'exclude'	=> true,
			'filter'	=> true,
			'inputType'	=> 'text',
			'eval'		=> array(
				'mandatory'	=> true,
				'maxlength'	=> 21,
				'rgxp'		=> 'digit',
				'tl_class'	=> 'w50'
			),
		),
		'price' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['price'],
			'exclude'	=> true,
			'search'	=> true,
			'inputType'	=> 'text',
			'eval'		=> array(
				'mandatory'	=> true,
				'maxlength'	=> 255,
				'rgxp'		=> 'digit',
				'tl_class'	=> 'clr'
			),
		),
		'published' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['published'],
			'exclude'	=> true,
			'filter'	=> true,
			'inputType'	=> 'checkbox',
			'eval'		=> array(
				'doNotCopy'	=> true,
			),
		),
		'start' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['start'],
			'exclude'	=> true,
			'inputType'	=> 'text',
			'eval'		=> array(
				'rgxp'		=> 'date',
				'datepicker'=> true,
				'tl_class'	=> 'w50 wizard'
			),
		),
		'stop' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimension_prices']['stop'],
			'exclude'	=> true,
			'inputType'	=> 'text',
			'eval'		=> array(
				'rgxp'		=> 'date',
				'datepicker'=> true,
				'tl_class'	=> 'w50 wizard'
			),
		),
	)
);
