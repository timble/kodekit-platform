
-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `terms_term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `table` varchar(50) NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `locked_by` int(10) unsigned DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`terms_term_id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `title` (`title`),
  KEY `table` (`table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `terms_relations`
--

CREATE TABLE IF NOT EXISTS `terms_relations` (
	`terms_term_id` BIGINT(20) UNSIGNED NOT NULL,
  	`row` BIGINT(20) UNSIGNED NOT NULL,
  	`table` VARCHAR( 255 ) NOT NULL,
  	PRIMARY KEY  (`terms_term_id`,`row`,`table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
