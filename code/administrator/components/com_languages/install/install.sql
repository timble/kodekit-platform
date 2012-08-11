CREATE TABLE IF NOT EXISTS `#__languages_items` (
    `languages_item_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `iso_code` VARCHAR(8) NOT NULL,
    `table` VARCHAR(150) NOT NULL,
    `row` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `created_on` DATETIME,
    `created_by` INT UNSIGNED,
    `modified_on` DATETIME,
    `modified_by` INT UNSIGNED,
    `status` SMALLINT NOT NULL DEFAULT 0,
    `original` BOOLEAN NOT NULL DEFAULT 0,
    `deleted` BOOLEAN NOT NULL DEFAULT 0,
    `params` TEXT,
    PRIMARY KEY (`languages_item_id`)
) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__languages_languages` (
	`languages_language_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  	`name` VARCHAR(150) NOT NULL,
  	`native_name` VARCHAR(150) NOT NULL,
	`iso_code` VARCHAR(8) NOT NULL,
	`slug` VARCHAR(255) NOT NULL,
	`created_on` DATETIME,
 	`created_by` INT UNSIGNED,
 	`locked_on` DATETIME,
 	`locked_by` INT UNSIGNED,
	`enabled` BOOLEAN NOT NULL DEFAULT 1,
	`image` VARCHAR(255),
	`ordering` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`languages_language_id`),
	UNIQUE KEY (`iso_code`),
	UNIQUE KEY (`slug`)
) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__languages_tables` (
    `languages_table_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `table_name` VARCHAR(150) NOT NULL,
    `unique_column` VARCHAR(150) NOT NULL,
    `title_column` VARCHAR(150) NOT NULL,
    `enabled` BOOLEAN NOT NULL DEFAULT 1,
    PRIMARY KEY (`languages_table_id`)
) ENGINE InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;