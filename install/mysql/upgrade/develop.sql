# -----------------------------------------------------------
# This script will update a Nooku 0.9 database to Nooku develop

SET FOREIGN_KEY_CHECKS=0;

UPDATE `pages` SET `link_url` = REPLACE(`link_url`, 'option=com_', 'component=');

UPDATE `pages_modules` SET `name` = REPLACE(`name`, 'mod_', '');

ALTER TABLE `users_groups` ADD COLUMN `uuid` char(36) NOT NULL AFTER `description`;

TRUNCATE `users_roles`;

ALTER TABLE `users_roles` ADD COLUMN `title` varchar(255) NOT NULL DEFAULT '' AFTER `name`;

INSERT INTO `users_roles` (`users_role_id`, `name`, `title`, `description`)
VALUES
    (1, 'registered', 'Registered', ''),
    (2, 'author', 'Author', ''),
    (3, 'editor', 'Editor', ''),
    (4, 'publisher', 'Publisher', ''),
    (5, 'manager', 'Manager', ''),
    (6, 'administrator', 'Administrator', '');

UPDATE `users` SET `users_role_id` = `users_role_id` - 17 WHERE `users_role_id` < 22;
UPDATE `users` SET `users_role_id` = `users_role_id` - 18 WHERE `users_role_id` > 22;

-- Downgrade Super Administrators to Administrators
UPDATE `users` SET `users_role_id` = 6 WHERE `users_role_id` = 7;

SET FOREIGN_KEY_CHECKS=1;