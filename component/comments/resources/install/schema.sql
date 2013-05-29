
-- --------------------------------------------------------
--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
    `comments_comment_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `table` VARCHAR(64) NOT NULL,
    `row` INT UNSIGNED NOT NULL,
    `text` TEXT,
    `created_on` DATETIME,
    `created_by` INT UNSIGNED,
    `modified_on` DATETIME,
    `modified_by` INT UNSIGNED,
    `locked_on` DATETIME,
    `locked_by` INT UNSIGNED,
    PRIMARY KEY (`comments_comment_id`),
    INDEX `idx_table_row` (`table`, `row`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;