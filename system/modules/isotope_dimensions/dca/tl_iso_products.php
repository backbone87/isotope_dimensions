<?php

$GLOBALS['TL_DCA']['tl_iso_products']['fields']['bbit_iso_dimension_inputUnit'] = array(
	'label'			=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_inputUnit'],
	'inputType'		=> 'text',
	'default'		=> array('cm', 'cm'),
	'eval'			=> array(
		'mandatory'			=> true,
		'maxlength'			=> 255,
		'multiple'			=> true,
		'size'				=> 2,
		'tl_class'			=> 'clr w50'
	),
	'attributes'	=> array(
		'legend'			=> 'pricing_legend',
		'fixed'				=> true,
		'inherit'			=> true,
	),
);
		
$GLOBALS['TL_DCA']['tl_iso_products']['fields']['bbit_iso_dimension_list'] = array(
	'label'			=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_list'],
	'inputType'		=> 'select',
	'foreignKey'	=> 'tl_iso_product_dimensions.name',
	'eval'			=> array(
		'mandatory'			=> true,
		'includeBlankOption'=> true,
		'tl_class'			=> 'clr w50'
	),
	'attributes'	=> array(
		'legend'			=> 'pricing_legend',
		'fixed'				=> true
	),
	'save_callback'	=> array(
		array('DimensionProductCallbacks', 'saveList')
	),
);
		
$GLOBALS['TL_DCA']['tl_iso_products']['fields']['bbit_iso_dimension_inputConversion'] = array(
	'label'			=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_inputConversion'],
	'inputType'		=> 'text',
	'default'		=> array(1, 1),
	'eval'			=> array(
		'mandatory'			=> true,
		'maxlength'			=> 64,
		'multiple'			=> true,
		'size'				=> 2,
		'rgxp'				=> 'digit',
		'tl_class'			=> 'w50'
	),
	'attributes'	=> array(
		'legend'			=> 'pricing_legend',
		'fixed'				=> true
	),
	'save_callback'	=> array(
		array('DimensionProductCallbacks', 'saveConversion')
	),
);

$GLOBALS['TL_DCA']['tl_iso_products']['fields']['bbit_iso_dimension_rules'] = array(
	'label'			=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_rules'],
	'exclude' 		=> true,
	'inputType' 	=> 'multiColumnWizard',
	'eval' 			=> array(
		'columnFields'		=> array(
			'x_min'	=> array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_x_min'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array('rgxp' => 'digit', 'maxlength' => 64, 'style' => 'width:100px')
			),
			'x_max'	=> array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_x_max'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array('rgxp' => 'digit', 'maxlength' => 64, 'style' => 'width:100px')
			),
			'y_min'	=> array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_y_min'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array('rgxp' => 'digit', 'maxlength' => 64, 'style' => 'width:100px')
			),
			'y_max'	=> array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_y_max'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array('rgxp' => 'digit', 'maxlength' => 64, 'style' => 'width:100px')
			),
			'area_min' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_area_min'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array('rgxp' => 'digit', 'maxlength' => 64, 'style' => 'width:100px')
			),
			'area_max' => array(
				'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_area_max'],
				'exclude'	=> true,
				'inputType'	=> 'text',
				'eval'		=> array('rgxp' => 'digit', 'maxlength' => 64, 'style' => 'width:100px')
			),
		),
		'tl_class'			=> 'clr',
	),
	'attributes'	=> array(
		'legend'			=> 'pricing_legend',
		'fixed'				=> true
	),
	'save_callback'	=> array(
		array('DimensionProductCallbacks', 'saveRules')
	),
);

$GLOBALS['TL_DCA']['tl_iso_products']['fields']['bbit_iso_dimension_input'] = array(
	'label'			=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_input'],
	'inputType'		=> 'bbit_iso_dimension',
	'eval'			=> array(
		'mandatory'			=> true,
		'ordinateLabels'	=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_input'],
	),
	'attributes'	=> array(
		'type'				=> 'bbit_iso_dimension',
		'variant_option'	=> true
	),
	'save_callback'	=> array(
		array('DimensionProductCallbacks', 'saveDimensions')
	),
);
