<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2011
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id: runonce.php 2367 2011-08-08 07:55:14Z aschempp $
 */


class IsotopeDimensionsRunonce extends Controller
{

	/**
	 * Initialize the object
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Fix potential Exception on line 0 because of __destruct method (see http://dev.contao.org/issues/2236)
		$this->import((TL_MODE=='BE' ? 'BackendUser' : 'FrontendUser'), 'User');
		$this->import('Database');
	}


	/**
	 * Execute all runonce files in module config directories
	 */
	public function run()
	{
		if ($this->Database->tableExists('tl_product_dimension_prices') && !$this->Database->tableExists('tl_iso_product_dimension_prices'))
		{
			$this->Database->query("ALTER TABLE tl_product_dimension_prices RENAME tl_iso_product_dimension_prices");
		}

		if ($this->Database->tableExists('tl_product_dimensions') && !$this->Database->tableExists('tl_iso_product_dimensions'))
		{
			$this->Database->query("ALTER TABLE tl_product_dimensions RENAME tl_iso_product_dimensions");
		}
	}
}

