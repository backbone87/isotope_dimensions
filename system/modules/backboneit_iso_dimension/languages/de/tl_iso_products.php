<?php

$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_inputUnit']
	= array('Eingabe-Einheit', 'Die Einheit in welcher die Eingaben vorgenommen werden.');
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_list']
	= array('Flächenpreise', 'Wählen Sie die Preisliste für diesen Artikel.');
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_inputConversion']
	= array('Eingabe-Umrechnung', 'Die Faktoren mit deren Hilfe die Eingabewerte von der Eingabeeinheit in die Einheit der Flächenpreis-Liste umgerechnet werden.');
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_rules']
	= array('Abmaßungs-Regeln (in Eingabe-Einheit, Fläche in erster Eingabe-Einheit)', 'Wenn angegeben, muss mindestens eine Regel auf die Eingabe zutreffen, damit die Eingabe als gültig anerkannt wird. Die eingesetzte Flächenpreis-Liste gibt einen impliziten Maximalwert für Breite und Höhe vor auf diese die angegeben Regeln automatisch eingeschränkt werden. Die impliziten Minimalwerte sind 0. Freigelassene Felder erhalten automatisch die impliziterten Werte.');
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_x_min']
	= array('Min. Breite', '');
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_x_max']
	= array('Max. Breite', '');
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_y_min']
	= array('Min. Höhe', '');
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_y_max']
	= array('Max. Höhe', '');
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_area_min']
	= array('Min. Fläche', '');
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_area_max']
	= array('Max. Fläche', '');

$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_input']
	= array('Maße', '');

//$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_errConversion']
//	= 'Beide Umrechnungswerte müssen angegeben werden und größer als 0 sein.';

$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_errPositive']
	= 'Geben Sie bitte nur positive Zahlen ein.';
	
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_noPrices']
	= 'In dieser Größe ist das Produkt nicht verfügbar.';
	
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_xAdjustMinMax']
	= 'Bei einer Höhe von %s muss die Breite zwischen %s und %s liegen.';
	
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_xAdjustMax']
	= 'Bei einer Höhe von %s beträgt die maximale Breite %s.';
	
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_yAdjustMinMax']
	= 'Bei einer Breite von %s muss die Höhe zwischen %s und %s liegen.';
	
$GLOBALS['TL_LANG']['tl_iso_products']['bbit_iso_dimension_yAdjustMax']
	= 'Bei einer Breite von %s beträgt die maximale Höhe %s.';
