
-- --------------------------------------------------------

--
-- Table structure for table `#__terms`
--

CREATE TABLE IF NOT EXISTS `#__terms` (
	`terms_term_id` bigint(20) unsigned NOT NULL auto_increment,
	`title` VARCHAR( 255 ) NOT NULL,
	`slug` VARCHAR( 255 ) NOT NULL,
	`params` text NOT NULL,
	`created_by` int(10) unsigned DEFAULT NULL,
    `created_on` datetime DEFAULT NULL,
    `modified_by` int(10) unsigned DEFAULT NULL,
    `modified_on` datetime DEFAULT NULL,
    `locked_by` int(10) unsigned DEFAULT NULL,
    `locked_on` datetime DEFAULT NULL,
	PRIMARY KEY ( `terms_term_id` ) ,
	UNIQUE KEY ( `slug` ),
	UNIQUE KEY ( `title` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__terms_relations`
--

CREATE TABLE IF NOT EXISTS `#__terms_relations` (
	`terms_term_id` BIGINT(20) UNSIGNED NOT NULL,
  	`row` BIGINT(20) UNSIGNED NOT NULL,
  	`table` VARCHAR( 255 ) NOT NULL,
  	PRIMARY KEY  (`terms_term_id`,`row`,`table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
