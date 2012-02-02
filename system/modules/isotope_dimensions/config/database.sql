-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

--
-- Table `tl_iso_product_dimensions`
--

CREATE TABLE `tl_iso_product_dimensions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `mode` varchar(16) NOT NULL default '',
  `unit` varchar(255) NOT NULL default '',
  `multiply_per` decimal(21,3) unsigned NOT NULL default '0.000',
  `multiply_unit` varchar(255) NOT NULL default '',
  `summarizeSize` varchar(8) NOT NULL default '',
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table `tl_iso_product_dimension_prices`
--

CREATE TABLE `tl_iso_product_dimension_prices` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `dimension_x` decimal(21,3) unsigned NOT NULL default '0.000',
  `dimension_y` decimal(21,3) unsigned NOT NULL default '0.000',
  `area` decimal(21,3) unsigned NOT NULL default '0.000',
  `price` decimal(21,3) unsigned NOT NULL default '0.000',
  `published` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_iso_products`
--

CREATE TABLE `tl_iso_products` (
  `dimensions` int(10) unsigned NOT NULL default '0',
  `dimensions_constrain` varchar(16) NOT NULL default '',
  `dimensions_ratio` decimal(21,3) unsigned NOT NULL default '0.000',
  `dimensions_min` blob NULL,
  `dimensions_max` blob NULL,
  `area_min` decimal(21,3) unsigned NOT NULL default '0.000',
  `area_max` decimal(21,3) unsigned NOT NULL default '0.000',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

