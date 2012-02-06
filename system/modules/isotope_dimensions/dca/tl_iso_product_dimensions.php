<?php

$GLOBALS['TL_DCA']['tl_iso_product_dimensions'] = array(

	'config' => array(
		'dataContainer'		=> 'Table',
		'enableVersioning'	=> true,
		'ctable'			=> array('tl_iso_product_dimension_prices'),
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
				'label'	=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['edit'],
				'href'	=> 'table=tl_iso_product_dimension_prices',
				'icon'	=> 'edit.gif',
			),
			'copy' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['copy'],
				'href'	=> 'act=copy',
				'icon'	=> 'copy.gif',
			),
			'delete' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['delete'],
				'href'	=> 'act=delete',
				'icon'	=> 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['show'],
				'href'	=> 'act=show',
				'icon'	=> 'show.gif',
			),
			/*'load' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['load'],
				'href'	=> 'key=load',
				'icon'	=> 'system/modules/isotope_dimensions/html/load-table.png',
			),*/
		)
	),

	'palettes' => array(
		'__selector__'	=> array('mode'),
	
		'default'		=> '{name_legend},name;'
			. '{config_legend},mode,unit;',
			
		/*'area'			=> '{name_legend},name;'
			. '{config_legend},mode,unit,multiply_per,multiply_unit;'
			. '{price_legend},summarizeSize',*/
	),
	
	'fields' => array(
		'name' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['name'],
			'inputType'	=> 'text',
			'eval'		=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
		),
		'mode' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['mode'],
			'filter'	=> true,
			'inputType'	=> 'radio',
			'default'	=> 'dimension_2d',
			'options'	=> array('dimension_2d'/*, 'area'*/),
			'reference'	=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions'],
			'eval'		=> array('mandatory'=>true, 'submitOnChange'=>true, 'tl_class'=>'clr w50'),
		),
		'unit' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['unit'],
			'filter'	=> true,
			'inputType'	=> 'text',
			'default'	=> 'cm',
			'eval'		=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
		),
		/*'multiply_per' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['multiply_per'],
			'filter'	=> true,
			'inputType'	=> 'text',
			'default'	=> 10000,
			'eval'		=> array('mandatory'=>true, 'rgxp'=>'digit', 'maxlength'=>21, 'tl_class'=>'clr w50'),
		),
		'multiply_unit' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['multiply_unit'],
			'filter'	=> true,
			'inputType'	=> 'text',
			'default'	=> 'm²',
			'eval'		=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
		),
		'summarizeSize' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['summarizeSize'],
			'filter'	=> true,
			'inputType'	=> 'radio',
			'default'	=> 'item',
			'options'	=> array('item', 'product', 'variant', 'type'),
			'reference'	=> &$GLOBALS['TL_LANG']['tl_iso_product_dimensions'],
			'eval'		=> array('mandatory'=>true, 'tl_class'=>'clr'),
		),*/
	)
);

