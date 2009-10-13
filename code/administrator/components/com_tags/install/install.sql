CREATE TABLE IF NOT EXISTS `#__tags_tags` (
	`tags_tag_id` SERIAL COMMENT 'Primary key',
	`name` VARCHAR( 255 ) NOT NULL COMMENT 'Name',
	`weight` VARCHAR( 255 ) NOT NULL COMMENT 'Weight',
	`params` text NOT NULL COMMENT 'Parameters',
	PRIMARY KEY ( `tags_tag_id` ) ,
	UNIQUE KEY ( `name` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'Tags';

CREATE TABLE IF NOT EXISTS `#__tags_maps` (
	`tags_map_id` SERIAL COMMENT 'Primary key',
	`tags_tag_id` BIGINT(20) UNSIGNED NOT NULL,
  	`row_id` BIGINT(20) UNSIGNED NOT NULL,	
  	`table_name` VARCHAR( 255 ) NOT NULL COMMENT 'Table name',
  	UNIQUE KEY `table_row_tag` (`table_name`, `row_id`, `tags_tag_id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT 'Link table for tagging';

-- --------------------------------------------------------

--
-- Dumping data for table `#_tags_tags`
--

INSERT INTO `#__tags_tags` VALUES(1, 'Marketing', '', '');
INSERT INTO `#__tags_tags` VALUES(2, 'Sales', '', '');
INSERT INTO `#__tags_tags` VALUES(3, 'Finance', '', '');
INSERT INTO `#__tags_tags` VALUES(4, 'Applications', '', '');
INSERT INTO `#__tags_tags` VALUES(5, 'Information Systems & Technology', '', '');

-- --------------------------------------------------------

--
-- Dumping data for table `#__tags_maps`
--

INSERT INTO `#__tags_maps` VALUES(1, 1, 2, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(2, 1, 3, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(3, 1, 4, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(4, 1, 5, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(5, 1, 6, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(6, 1, 7, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(7, 1, 8, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(8, 1, 9, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(9, 1, 10, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(10, 1, 11, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(11, 2, 12, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(12, 2, 13, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(13, 2, 14, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(14, 2, 15, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(15, 2, 16, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(16, 2, 17, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(17, 2, 18, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(18, 2, 19, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(19, 2, 20, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(20, 2, 21, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(21, 3, 22, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(22, 3, 23, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(23, 3, 24, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(24, 3, 25, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(25, 3, 26, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(26, 3, 27, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(27, 3, 28, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(28, 3, 29, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(29, 3, 30, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(30, 3, 31, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(31, 4, 32, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(32, 4, 33, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(33, 4, 34, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(34, 4, 35, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(35, 4, 36, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(36, 4, 37, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(37, 4, 38, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(38, 4, 39, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(39, 4, 40, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(40, 4, 41, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(41, 5, 42, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(42, 5, 43, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(43, 5, 44, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(44, 5, 45, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(45, 5, 46, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(46, 5, 47, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(47, 5, 48, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(48, 5, 49, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(49, 5, 50, 'profiles_people');
INSERT INTO `#__tags_maps` VALUES(50, 5, 51, 'profiles_people');