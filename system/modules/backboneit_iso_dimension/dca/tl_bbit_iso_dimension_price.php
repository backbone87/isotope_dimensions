<?php

$GLOBALS['TL_DCA']['tl_bbit_iso_dimension_price'] = array(

	'config' => array(
		'dataContainer'		=> 'Table',
		'enableVersioning'	=> true,
		'ptable'			=> 'tl_bbit_iso_dimension',
		'onload_callback'	=> array(
			array('Dimension2DProductCallbacks', 'selectPalette'),
		),
	),

	'list' => array(
		'sorting' => array(
			'mode'					=> 4,
			'fields'				=> array('dimension_x', 'dimension_y'),
			'flag'					=> 1,
			'panelLayout'			=> 'filter;search,limit',
			'headerFields'			=> array('name', 'mode', 'pricePerUnit', 'unit'),
			'disableGrouping'		=> true,
			'child_record_callback'	=> array('Dimension2DProductCallbacks', 'listPrice')
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
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['edit'],
				'href'	=> 'act=edit',
				'icon'	=> 'edit.gif'
			),
			'copy' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['copy'],
				'href'	=> 'act=copy',
				'icon'	=> 'copy.gif'
			),
			'delete' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['delete'],
				'href'	=> 'act=delete',
				'icon'	=> 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['show'],
				'href'	=> 'act=show',
				'icon'	=> 'show.gif'
			),
		)
	),

	'palettes' => array(
		'dimension_2d'	=> '{dimension_legend},dimension_x,dimension_y;'
			. '{price_legend},price;'
			. '{publish_legend},published,start,stop',
			
		'content'		=> '{dimension_legend},content;'
			. '{price_legend},price;'
			. '{publish_legend},published,start,stop',
	),

	'fields' => array(
		'dimension_x' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['dimension_x'],
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
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['dimension_y'],
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
		'content' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['content'],
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
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['price'],
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
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['published'],
			'exclude'	=> true,
			'filter'	=> true,
			'inputType'	=> 'checkbox',
			'eval'		=> array(
				'doNotCopy'	=> true,
			),
		),
		'start' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['start'],
			'exclude'	=> true,
			'inputType'	=> 'text',
			'eval'		=> array(
				'rgxp'		=> 'date',
				'datepicker'=> true,
				'tl_class'	=> 'clr w50 wizard'
			),
		),
		'stop' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension_price']['stop'],
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
