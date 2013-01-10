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