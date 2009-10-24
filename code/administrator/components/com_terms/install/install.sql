CREATE TABLE IF NOT EXISTS `#__terms_terms` (
	`terms_term_id` SERIAL COMMENT 'Primary key',
	`name` VARCHAR( 255 ) NOT NULL COMMENT 'Name',
	`alias` VARCHAR( 255 ) NOT NULL COMMENT 'Alias',
	`params` text NOT NULL COMMENT 'Parameters',
	PRIMARY KEY ( `terms_term_id` ) ,
	UNIQUE KEY ( `name` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'Terms table for taxonomy ';

CREATE TABLE IF NOT EXISTS `#__terms_relationships` (
	`terms_relationship_id` SERIAL COMMENT 'Primary key',
	`terms_term_id` BIGINT(20) UNSIGNED NOT NULL,
  	`row_id` BIGINT(20) UNSIGNED NOT NULL,	
  	`table_name` VARCHAR( 255 ) NOT NULL COMMENT 'Table name',
  	UNIQUE KEY `table_row_tag` (`table_name`, `row_id`, `terms_term_id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT 'Relationships table for taxonomy';

-- --------------------------------------------------------

--
-- Dumping data for table `#_terms_terms`
--

INSERT INTO `#__terms_terms` VALUES(1, 'Marketing', 'marketing', '');
INSERT INTO `#__terms_terms` VALUES(2, 'Sales', 'sales', '');
INSERT INTO `#__terms_terms` VALUES(3, 'Finance', 'finance', '');
INSERT INTO `#__terms_terms` VALUES(4, 'Applications', 'applications', '');
INSERT INTO `#__terms_terms` VALUES(5, 'Information Systems & Technology', 'information-systems-technology', '');

-- --------------------------------------------------------

--
-- Dumping data for table `#__terms_relationships`
--

INSERT INTO `#__terms_relationships` VALUES(1, 1, 2, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(2, 1, 3, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(3, 1, 4, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(4, 1, 5, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(5, 1, 6, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(6, 1, 7, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(7, 1, 8, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(8, 1, 9, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(9, 1, 10, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(10, 1, 11, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(11, 2, 12, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(12, 2, 13, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(13, 2, 14, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(14, 2, 15, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(15, 2, 16, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(16, 2, 17, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(17, 2, 18, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(18, 2, 19, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(19, 2, 20, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(20, 2, 21, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(21, 3, 22, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(22, 3, 23, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(23, 3, 24, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(24, 3, 25, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(25, 3, 26, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(26, 3, 27, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(27, 3, 28, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(28, 3, 29, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(29, 3, 30, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(30, 3, 31, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(31, 4, 32, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(32, 4, 33, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(33, 4, 34, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(34, 4, 35, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(35, 4, 36, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(36, 4, 37, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(37, 4, 38, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(38, 4, 39, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(39, 4, 40, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(40, 4, 41, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(41, 5, 42, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(42, 5, 43, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(43, 5, 44, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(44, 5, 45, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(45, 5, 46, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(46, 5, 47, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(47, 5, 48, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(48, 5, 49, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(49, 5, 50, 'profiles_people');
INSERT INTO `#__terms_relationships` VALUES(50, 5, 51, 'profiles_people');