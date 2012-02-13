-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

CREATE TABLE `tl_bbit_iso_dimension` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `mode` varchar(255) NOT NULL default '',
  `pricePerUnit` char(1) NOT NULL default '',
  `unit` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `tl_bbit_iso_dimension_price` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `dimension_x` decimal(21,3) unsigned NOT NULL default '0.000',
  `dimension_y` decimal(21,3) unsigned NOT NULL default '0.000',
  `content` decimal(21,3) unsigned NOT NULL default '0.000',
  `price` decimal(21,3) unsigned NOT NULL default '0.000',
  `published` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`, `price`),
  KEY `published` (`pid`, `published`, `start`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `tl_iso_products` (
  `bbit_iso_dimension_list` int(10) unsigned NOT NULL default '0',
  `bbit_iso_dimension_inputUnit` blob NULL,
  `bbit_iso_dimension_inputConversion` blob NULL,
  `bbit_iso_dimension_rules` blob NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tl_iso_producttypes` (
  `bbit_iso_dimension_labels` varchar(255) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
