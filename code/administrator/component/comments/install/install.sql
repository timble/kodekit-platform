CREATE TABLE `#__comments_comments` (
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

CREATE VIEW `#__comments_view_comments` AS
    SELECT
        `comment`.*,
        `creator`.`name` AS `created_by_name`,
        `creator`.`email` AS `created_by_email`
    FROM
        `#__comments_comments` AS `comment`
    LEFT JOIN
        `#__users` AS `creator` ON `creator`.`id` = `comment`.`created_by`;