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