<?php

array_insert($GLOBALS['BE_MOD']['isotope'], 1, array(
	'bbit_iso_dimension' => array(
		'tables'		=> array('tl_bbit_iso_dimension', 'tl_bbit_iso_dimension_price'),
		'icon'			=> 'system/modules/backboneit_iso_dimension/html/icon-dimensions.png',
	),
));

$GLOBALS['ISO_PRODUCT']['bbit_iso_dimension_2d'] = array(
	'class'				=> 'Dimension2DProduct',
	'disabledFields'	=> array('price', 'bbit_iso_dimension_2d_input'),
);

$GLOBALS['ISO_PRODUCT']['regular']['disabledFields'][] = 'bbit_iso_dimension_2d_input';
$GLOBALS['ISO_PRODUCT']['regular']['disabledFields'][] = 'bbit_iso_dimension_list';
$GLOBALS['ISO_PRODUCT']['regular']['disabledFields'][] = 'bbit_iso_dimension_inputUnit';
$GLOBALS['ISO_PRODUCT']['regular']['disabledFields'][] = 'bbit_iso_dimension_inputConversion';
$GLOBALS['ISO_PRODUCT']['regular']['disabledFields'][] = 'bbit_iso_dimension_rules';
