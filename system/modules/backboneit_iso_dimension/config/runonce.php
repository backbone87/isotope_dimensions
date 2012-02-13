<?php

$objConn = Database::getInstance();

$arrTableRename = array(
	array(
		'old'	=> 'tl_product_dimension_prices',
		'new'	=> 'tl_iso_product_dimension_prices',
	),
	array(
		'old'	=> 'tl_iso_product_dimension_prices',
		'new'	=> 'tl_bbit_iso_dimension_price',
	),
	array(
		'old'	=> 'tl_product_dimensions',
		'new'	=> 'tl_iso_product_dimensions',
	),
	array(
		'old'	=> 'tl_iso_product_dimensions',
		'new'	=> 'tl_bbit_iso_dimension',
	),
);

foreach($arrTableRename as $arrRename) {
	if(!$objConn->tableExists($arrRename['old'])) {
		continue;
	}
	if($objConn->tableExists($arrRename['new'])) {
		continue;
	}
	$objConn->query('ALTER TABLE `' . $arrRename['old'] . '` RENAME `' . $arrRename['new'] . '`');
}

$arrValueTransform = array(
	array(
		'table'		=> 'tl_iso_producttypes',
		'column'	=> 'class',
		'old'		=> array('dimension', 'bbit_iso_dimension'),
		'new'		=> 'bbit_iso_dimension_2d',
	),
	array(
		'table'		=> 'tl_bbit_iso_dimension',
		'column'	=> 'mode',
		'old'		=> array('dimensions'),
		'new'		=> 'dimension_2d',
	),
	array(
		'table'		=> 'tl_bbit_iso_dimension',
		'column'	=> 'mode',
		'old'		=> array('area'),
		'new'		=> 'content',
	),
);

foreach($arrValueTransform as $arrTransform) {
	if(!$objConn->tableExists($arrTransform['table'])) {
		continue;
	}
	if(!$objConn->fieldExists($arrTransform['column'], $arrTransform['table'])) {
		continue;
	}
	$arrParams = (array) $arrTransform['old'];
	$strWildcards = implode(',', array_fill(0, count($arrParams), '?'));
	array_unshift($arrParams, $arrTransform['new']);
	$objConn->prepare(
		'UPDATE `' . $arrTransform['table'] . '`
		SET `' . $arrTransform['column'] . '` = ?
		WHERE `' . $arrTransform['column'] . '` IN (' . $strWildcards . ')'
	)->execute($arrParams);
}
//*/