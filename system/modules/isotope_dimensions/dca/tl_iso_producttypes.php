<?php

$GLOBALS['TL_DCA']['tl_iso_producttypes']['palettes']['bbit_iso_dimension']
	= '{name_legend},name,class,fallback;'
	. '{dimension_legend},bbit_iso_dimension_input,bbit_iso_dimension_listType;'
	. '{description_legend:hide},description;'
	. '{template_legend},list_template,reader_template;'
	. '{attributes_legend},attributes,variants;'
	. '{download_legend:hide},downloads';

$GLOBALS['TL_DCA']['tl_iso_products']['fields']['bbit_iso_dimension_inputType'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_inputType'],
	'inputType'	=> 'select',
	'default'	=> 'dimension_2d',
	'options'	=> array('dimension_2d'/*, 'dimension_3d',*/),
	'reference'	=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_inputTypeOptions'],
	'eval'		=> array('mandatory' => true, 'tl_class' => 'w50')
);
	
$GLOBALS['TL_DCA']['tl_iso_products']['fields']['bbit_iso_dimension_listType'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_listType'],
	'inputType'	=> 'select',
	'default'	=> 'dimension',
	'options'	=> array('dimension'/*, 'content'*/),
	'reference'	=> &$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_listTypeOptions'],
	'eval'		=> array('mandatory' => true, 'tl_class' => 'w50')
);
