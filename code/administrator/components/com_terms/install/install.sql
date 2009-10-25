CREATE TABLE IF NOT EXISTS `#__terms_terms` (
	`terms_term_id` SERIAL COMMENT 'Primary key',
	`name` VARCHAR( 255 ) NOT NULL COMMENT 'Name',
	`alias` VARCHAR( 255 ) NOT NULL COMMENT 'Alias',
	`params` text NOT NULL COMMENT 'Parameters',
	PRIMARY KEY ( `terms_term_id` ) ,
	UNIQUE KEY ( `name` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'Terms table for taxonomy ';

CREATE TABLE IF NOT EXISTS `#__terms_relations` (
	`terms_relation_id` SERIAL COMMENT 'Primary key',
	`terms_term_id` BIGINT(20) UNSIGNED NOT NULL,
  	`row_id` BIGINT(20) UNSIGNED NOT NULL,	
  	`table_name` VARCHAR( 255 ) NOT NULL COMMENT 'Table name',
  	UNIQUE KEY `table_row_tag` (`table_name`, `row_id`, `terms_term_id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT 'Relations table for taxonomy';

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
-- Dumping data for table `#__terms_relations`
--

INSERT INTO `#__terms_relations` VALUES(1, 1, 2, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(2, 1, 3, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(3, 1, 4, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(4, 1, 5, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(5, 1, 6, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(6, 1, 7, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(7, 1, 8, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(8, 1, 9, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(9, 1, 10, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(10, 1, 11, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(11, 2, 12, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(12, 2, 13, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(13, 2, 14, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(14, 2, 15, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(15, 2, 16, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(16, 2, 17, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(17, 2, 18, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(18, 2, 19, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(19, 2, 20, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(20, 2, 21, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(21, 3, 22, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(22, 3, 23, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(23, 3, 24, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(24, 3, 25, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(25, 3, 26, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(26, 3, 27, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(27, 3, 28, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(28, 3, 29, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(29, 3, 30, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(30, 3, 31, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(31, 4, 32, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(32, 4, 33, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(33, 4, 34, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(34, 4, 35, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(35, 4, 36, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(36, 4, 37, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(37, 4, 38, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(38, 4, 39, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(39, 4, 40, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(40, 4, 41, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(41, 5, 42, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(42, 5, 43, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(43, 5, 44, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(44, 5, 45, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(45, 5, 46, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(46, 5, 47, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(47, 5, 48, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(48, 5, 49, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(49, 5, 50, 'profiles_people');
INSERT INTO `#__terms_relations` VALUES(50, 5, 51, 'profiles_people');