<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2010
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id: tl_iso_product_dimensions.php 2024 2011-01-28 14:44:28Z aschempp $
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['name']			= array('Naam', 'Kies de naam voor deze prijsgroep.');
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['mode']			= array('Modus', 'Kies de bereken modus.');
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['multiply_per']	= array('Vermenigvuldigingsfactor', 'Kies een vermenigvuldigingsfactor (bv; als 100 * 100cm = 1m2, dan geldt 10000 = prijs * m2)');
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['multiply_unit']	= array('Vermenigvuldigingseenheid', 'Kies een vermenigvuldigingseenheid.');
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['unit']			= array('Eenheid', 'Kies een eenheidsmaat.');
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['summarizeSize']	= array('Staffel berekening', 'Kies een methode om de staffel te berekenen.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['new']				= array('Nieuwe prijsgroep', 'Creeër eem nieuwe prijsgroep');
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['edit']			= array('Pas prijsgroep aan', 'Pas prijsgroep ID %s aan');
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['copy']			= array('Kopieer prijsgroep', 'Kopieer prijsgroep ID %s');
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['delete']			= array('Verwijder prijsgroep', 'Verwijder prijsgroep ID %s');
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['show']			= array('Prijsgroep details', 'Pas de details van prijsgroep ID %s aan');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['name_legend']		= 'Naam';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['config_legend']	= 'Configuratie';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['price_legend']	= 'Prijs';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['dimensions']		= 'Breedte en hoogte';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['area']			= 'Oppervlakte';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['mm']				= 'Milimeter (mm)';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['cm']				= 'Centimeter (cm)';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['m']				= 'Meter (m)';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['km']				= 'Kilometer (km)';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['qmm']				= 'mm<sup>2</sup>';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['qcm']				= 'cm<sup>2</sup>';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['qm']				= 'm<sup>2</sup>';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['qkm']				= 'km<sup>2</sup>';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['mm_label']		= 'mm';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['cm_label']		= 'cm';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['m_label']			= 'm';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['km_label']		= 'km';

$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['item']			= 'per item';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['product']			= 'per product';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['variant']			= 'per variatie';
$GLOBALS['TL_LANG']['tl_iso_product_dimensions']['type']			= 'per producttype';

