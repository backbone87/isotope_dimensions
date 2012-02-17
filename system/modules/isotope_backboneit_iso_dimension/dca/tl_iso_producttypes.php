<?php

$GLOBALS['TL_DCA']['tl_iso_producttypes']['palettes']['bbit_iso_dimension_2d']
	= '{name_legend},name,class,fallback;'
	. '{bbit_iso_dimension_legend},bbit_iso_dimension_labels;'
	. '{description_legend:hide},description;'
	. '{template_legend},list_template,reader_template;'
	. '{attributes_legend},attributes,variants;'
	. '{download_legend:hide},downloads';

$GLOBALS['TL_DCA']['tl_iso_producttypes']['fields']['bbit_iso_dimension_labels'] = array(
	'label'			=> &$GLOBALS['TL_LANG']['tl_iso_producttypes']['bbit_iso_dimension_labels'],
	'inputType'		=> 'text',
	'default'		=> array('x', '(in %s)', 'y', '(in %s)'),
	'eval'			=> array(
		'mandatory'			=> true,
		'maxlength'			=> 64,
		'multiple'			=> true,
		'size'				=> 4,
		'tl_class'			=> 'long'
	),
);
