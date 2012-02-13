<?php

array_insert($GLOBALS['BE_MOD']['isotope'], 1, array(
	'iso_dimensions' => array(
		'tables'		=> array('tl_iso_product_dimensions', 'tl_iso_product_dimension_prices'),
		'icon'			=> 'system/modules/isotope_dimensions/html/icon-dimensions.png',
	),
));

$GLOBALS['ISO_PRODUCT']['bbit_iso_dimension'] = array(
	'class'				=> 'DimensionProduct',
	'disabledFields'	=> array('price', 'bbit_iso_dimension_input'),
);

$GLOBALS['ISO_PRODUCT']['regular']['disabledFields'][] = 'bbit_iso_dimension_input';
$GLOBALS['ISO_PRODUCT']['regular']['disabledFields'][] = 'bbit_iso_dimension_list';
$GLOBALS['ISO_PRODUCT']['regular']['disabledFields'][] = 'bbit_iso_dimension_inputUnit';
$GLOBALS['ISO_PRODUCT']['regular']['disabledFields'][] = 'bbit_iso_dimension_inputConversion';
$GLOBALS['ISO_PRODUCT']['regular']['disabledFields'][] = 'bbit_iso_dimension_rules';

//$GLOBALS['ISO_HOOKS']['generateProduct'][] = array('DimensionProductCallbacks', 'addPriceToProduct');

