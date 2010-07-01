-- $Id$

CREATE TABLE IF NOT EXISTS `#__terms_terms` (
	`terms_term_id` bigint(20) unsigned NOT NULL auto_increment,
	`title` VARCHAR( 255 ) NOT NULL,
	`slug` VARCHAR( 255 ) NOT NULL,
	`params` text NOT NULL,
	`created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  	`created_by` int(11) NOT NULL default 0,
 	`modified_on` datetime NOT NULL default '0000-00-00 00:00:00',
  	`modified_by` int(11) NOT NULL default 0,
  	`locked_on` datetime NOT NULL default '0000-00-00 00:00:00',
  	`locked_by` int(11) NOT NULL default 0,
	PRIMARY KEY ( `terms_term_id` ) ,
	UNIQUE KEY ( `slug` ),
	UNIQUE KEY ( `title` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'Terms table for taxonomy ';

CREATE TABLE IF NOT EXISTS `#__terms_relations` (
	`terms_term_id` BIGINT(20) UNSIGNED NOT NULL,
  	`row` BIGINT(20) UNSIGNED NOT NULL,	
  	`table` VARCHAR( 255 ) NOT NULL,
  	PRIMARY KEY  (`terms_term_id`,`row`,`table`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT 'Relations table for taxonomy';

-- --------------------------------------------------------

--
-- Dumping data for table `#_terms_terms`
--

INSERT IGNORE INTO `#__terms_terms` VALUES(1, 'Marketing', 'marketing', '');
INSERT IGNORE INTO `#__terms_terms` VALUES(2, 'Sales', 'sales', '');
INSERT IGNORE INTO `#__terms_terms` VALUES(3, 'Finance', 'finance', '');
INSERT IGNORE INTO `#__terms_terms` VALUES(4, 'Applications', 'applications', '');
INSERT IGNORE INTO `#__terms_terms` VALUES(5, 'Information Systems & Technology', 'information-systems-technology', '');

-- --------------------------------------------------------

--
-- Dumping data for table `#__terms_relations`
--

INSERT IGNORE INTO `#__terms_relations` VALUES(1, 2, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(1, 3, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(1, 4, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(1, 5, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(1, 6, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(1, 7, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(1, 8, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(1, 9, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(1, 10, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(1, 11, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(2, 12, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(2, 13, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(2, 14, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(2, 15, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(2, 16, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(2, 17, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(2, 18, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(2, 19, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(2, 20, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(2, 21, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(3, 22, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(3, 23, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(3, 24, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(3, 25, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(3, 26, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(3, 27, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(3, 28, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(3, 29, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(3, 30, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(3, 31, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(4, 32, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(4, 33, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(4, 34, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(4, 35, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(4, 36, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(4, 37, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(4, 38, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(4, 39, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(4, 40, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(4, 41, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(5, 42, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(5, 43, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(5, 44, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(5, 45, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(5, 46, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(5, 47, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(5, 48, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(5, 49, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(5, 50, 'profiles_people');
INSERT IGNORE INTO `#__terms_relations` VALUES(5, 51, 'profiles_people');