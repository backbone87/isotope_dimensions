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
 * @version    $Id: tl_iso_products.php 1968 2011-01-15 12:55:02Z aschempp $
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_iso_products']['dimensions']			= array('Surface area price', 'Select the price list for this article.');
$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_ratio']		= array('Ratio', 'Use this together with "Constrain" to constrain the aspect ratio. Ratio = dimension_a / dimension_b (depends upon the setting for Constrain)');
$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_constrain']	= array('Constrain', 'Choose which dimension to constrain. Users will not be able to change this value because it is locked to the other dimension.');
$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_min']		= array('Minimum dimensions ', 'Minimum width and height that can be ordered.');
$GLOBALS['TL_LANG']['tl_iso_products']['dimensions_max']		= array('Maximum dimensions', 'Maximum width and height that can be ordered.');
$GLOBALS['TL_LANG']['tl_iso_products']['area_min']				= array('Minimum surface area', 'Minimum surface area that can be ordered.');
$GLOBALS['TL_LANG']['tl_iso_products']['area_max']				= array('Maximum surface area', 'Maximum surface area that can be ordered.');
$GLOBALS['TL_LANG']['tl_iso_products']['dimension_x']			= array('Width');
$GLOBALS['TL_LANG']['tl_iso_products']['dimension_y']			= array('Height');
$GLOBALS['TL_LANG']['tl_iso_products']['constrain_x']			= array('Constrain width');
$GLOBALS['TL_LANG']['tl_iso_products']['constrain_y']			= array('Constrain height');
$GLOBALS['TL_LANG']['tl_iso_products']['mm_label']				= 'mm';
$GLOBALS['TL_LANG']['tl_iso_products']['cm_label']				= 'cm';
$GLOBALS['TL_LANG']['tl_iso_products']['m_label']				= 'm';
$GLOBALS['TL_LANG']['tl_iso_products']['km_label']				= 'km';

