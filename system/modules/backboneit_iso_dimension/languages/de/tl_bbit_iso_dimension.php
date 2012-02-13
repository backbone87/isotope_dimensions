<?php

$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['name']
	= array('Name', 'Der Name der Liste zur Anzeige im Backend.');
	
$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['mode']
	= array('Listentyp', 'Der Listentyp bestimmt, wie Maße in der Liste angegeben werden.');
$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['modeOptions'] = array(
	'dimension_2d'	=> 'Zweidimensionale Maße',
	'content'		=> 'Inhaltsmaß'
);
	
$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['pricePerUnit']
	= array('Preis verhältnismäßig an Größe anpassen', 'Der Preis der angewendeten Maßregel wird '
	. 'auf den eigentlichen Inhalt heruntergerechnet. <strong>Die Preise müssen in diesem Falle '
	. 'pro Inhaltseinheit angegeben werden.</strong> Beispiel: Für ein Produkt mit der '
	. 'Größe 10cm x 10cm wird die Regel 20cm x 20cm angewendet, die pro cm² einen Preis von 1€ hat. '
	. 'Dann kostet das Produkt anstatt 400€ (20cm x 20cm x 1€/cm²) nur 100€ (10cm x 10cm x 1€/cm²).');
	
$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['unit']
	= array('Einheit', 'Die Einheit der Maße dieser Liste. Diese wird nur im Backend zur Pflege der Liste verwendet.');

$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['new']
	= array('Neue Preisliste', 'Erstellen Sie eine neue Preisliste');
$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['edit']
	= array('Preisliste bearbeiten', 'Preisliste ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['copy']
	= array('Preisliste kopieren', 'Preisliste ID %s kopieren');
$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['delete']
	= array('Preisliste löschen', 'Preisliste ID %s löschen');
$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['show']
	= array('Preislistendetails', 'Details der Preisliste ID %s anzeigen');

$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['name_legend']
	= 'Name';
$GLOBALS['TL_LANG']['tl_bbit_iso_dimension']['config_legend']
	= 'Konfiguration';
