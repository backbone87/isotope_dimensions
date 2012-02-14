<?php

$GLOBALS['TL_DCA']['tl_bbit_iso_dimension'] = array(

	'config' => array(
		'dataContainer'		=> 'Table',
		'enableVersioning'	=> true,
		'ctable'			=> array('tl_bbit_iso_dimension_price'),
		'switchToEdit'		=> true,
	),

	'list' => array(
		'sorting' => array(
			'mode'			=> 1,
			'fields'		=> array('name'),
			'flag'			=> 1,
			'panelLayout'	=> 'filter;search,limit',
		),
		'label' => array(
			'fields'	=> array('name'),
			'format'	=> '%s',
		),
		'global_operations' => array(
			'all' => array(
				'label'	=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'	=> 'act=select',
				'class'	=> 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();"',
			)
		),
		'operations' => array(
			'edit' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['edit'],
				'href'	=> 'table=tl_bbit_iso_dimension_price',
				'icon'	=> 'edit.gif',
			),
			'copy' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['copy'],
				'href'	=> 'act=copy',
				'icon'	=> 'copy.gif',
			),
			'delete' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['delete'],
				'href'	=> 'act=delete',
				'icon'	=> 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['show'],
				'href'	=> 'act=show',
				'icon'	=> 'show.gif',
			),
		)
	),

	'palettes' => array(
//		'__selector__'	=> array('mode'),
	
		'default'		=> '{name_legend},name;'
			. '{config_legend},mode,pricePerUnit,unit;',
	),
	
	'fields' => array(
		'name' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['name'],
			'inputType'	=> 'text',
			'eval'		=> array(
				'mandatory'	=> true,
				'maxlength'	=> 255,
				'tl_class'	=> 'long'
			),
		),
		'mode' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['mode'],
			'filter'	=> true,
			'inputType'	=> 'radio',
			'default'	=> 'dimension_2d',
			'options'	=> array('dimension_2d', 'content'),
			'reference'	=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['modeOptions'],
			'eval'		=> array(
				'mandatory'	=> true,
//				'submitOnChange' => true,
				'tl_class'	=> 'clr w50'
			),
		),
		'pricePerUnit' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['pricePerUnit'],
			'filter'	=> true,
			'inputType'	=> 'checkbox',
			'eval'		=> array(
				'tl_class'	=> 'w50'
			),
		),
		'unit' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['unit'],
			'filter'	=> true,
			'inputType'	=> 'text',
			'default'	=> 'cm',
			'eval'		=> array(
				'mandatory'	=> true,
				'maxlength'	=> 255,
				'tl_class'	=> 'clr w50'
			),
		),
	)
);

