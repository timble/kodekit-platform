# -----------------------------------------------------------
# This script will update a Joomla 1.5 database to Nooku 13.1

-- Remove all the admin modules
DELETE FROM `modules` WHERE `client_id` = 1;

-- Remove unused site modules
DELETE FROM `modules` WHERE `module` = 'mod_related_items';
DELETE FROM `modules` WHERE `module` = 'mod_syndicate';
DELETE FROM `modules` WHERE `module` = 'mod_footer';
DELETE FROM `modules` WHERE `module` = 'mod_wrapper';
DELETE FROM `modules` WHERE `module` = 'mod_stats';
DELETE FROM `modules` WHERE `module` = 'mod_whosonline';
DELETE FROM `modules` WHERE `module` = 'mod_sections';
DELETE FROM `modules` WHERE `module` = 'mod_quickicon';
DELETE FROM `modules` WHERE `module` = 'mod_mostread';
DELETE FROM `modules` WHERE `module` = 'mod_archive';
DELETE FROM `modules` WHERE `module` = 'mod_random_image';

ALTER TABLE `modules` DROP `iscore`;
ALTER TABLE `modules` DROP `control`;
ALTER TABLE `modules` DROP `numnews`;

ALTER TABLE `modules` CHANGE  `client_id`  `application` VARCHAR( 50 ) NOT NULL;
ALTER TABLE `modules` ADD  `extensions_component_id` INT( 10 ) NOT NULL AFTER  `params`;
ALTER TABLE `modules` CHANGE  `module`  `name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `modules` DROP INDEX newsfeeds;

UPDATE `modules` SET `application` = 'site' WHERE `application` = 0;

UPDATE `modules` SET `extensions_extension_id` = 31 WHERE `name` = 'mod_login';
UPDATE `modules` SET `extensions_extension_id` = 25 WHERE `name` = 'mod_mainmenu';
UPDATE `modules` SET `extensions_extension_id` = 25 WHERE `name` = 'mod_breadcrumbs';
UPDATE `modules` SET `extensions_extension_id` = 25 WHERE `name` = 'mod_custom';

UPDATE `modules` SET `params` = CONCAT('show_title=', `showtitle`, '\n', `params`) WHERE `application` = 'site' AND `name` IN ('mod_newsflash', 'mod_login', 'mod_menu', 'mod_custom', 'mod_latestnews');
ALTER TABLE `modules` DROP `showtitle`;

UPDATE `modules` SET `params` = REPLACE(`params`, 'showAllChildren=', 'show_children=') WHERE `name` = 'mod_menu';
UPDATE `modules` SET `params` = REPLACE(`params`, 'endLevel=', 'end_level=') WHERE `name` = 'mod_menu';
UPDATE `modules` SET `params` = REPLACE(`params`, 'startLevel=', 'start_level=') WHERE `name` = 'mod_menu';

UPDATE `modules` SET `params` = REPLACE(`params`, CONCAT('end_level=', SUBSTRING_INDEX(SUBSTRING_INDEX(`params`, 'end_level=', -1), '\n', 1)), CONCAT('end_level=', SUBSTRING_INDEX(SUBSTRING_INDEX(`params`, 'start_level=', -1), '\n', 1) + 1)) WHERE `name` = 'mod_menu' AND `params` LIKE '%show_children=0%' AND `params` LIKE '%expand_menu=0%';
UPDATE `modules` SET `params` = REPLACE(`params`, 'show_children=1', 'show_children=always') WHERE `name` = 'mod_menu';
UPDATE `modules` SET `params` = REPLACE(`params`, 'show_children=0', 'show_children=active') WHERE `name` = 'mod_menu';
UPDATE `modules` SET `params` = REPLACE(`params`, CONCAT('start_level=', SUBSTRING_INDEX(SUBSTRING_INDEX(`params`, 'start_level=', -1), '\n', 1)), CONCAT('start_level=', SUBSTRING_INDEX(SUBSTRING_INDEX(`params`, 'start_level=', -1), '\n', 1) + 1)) WHERE `name` = 'mod_menu' AND `params` LIKE '%start_level=%';
UPDATE `modules` SET `params` = REPLACE(`params`, CONCAT('end_level=', SUBSTRING_INDEX(SUBSTRING_INDEX(`params`, 'end_level=', -1), '\n', 1)), CONCAT('end_level=', SUBSTRING_INDEX(SUBSTRING_INDEX(`params`, 'end_level=', -1), '\n', 1) + 1)) WHERE `name` = 'mod_menu' AND `params` LIKE '%end_level=%';

# --------------------------------------------------------

-- Remove tables
DROP TABLE `groups`;
DROP TABLE `plugins`;

# --------------------------------------------------------

-- Remove unused components
DELETE FROM `components` WHERE `option` = 'com_wrapper';
DELETE FROM `components` WHERE `option` = 'com_massmail';
DELETE FROM `components` WHERE `option` = 'com_mailto';
DELETE FROM `components` WHERE `option` = 'com_templates';
DELETE FROM `components` WHERE `option` = 'com_messages';
DELETE FROM `components` WHERE `option` = 'com_user';
DELETE FROM `components` WHERE `option` = 'com_config';
DELETE FROM `components` WHERE `option` = 'com_plugins';
DELETE FROM `components` WHERE `option` = 'com_cpanel';

# --------------------------------------------------------

-- Rename contacts_details to contacts_contacts
RENAME TABLE  `contact_details` TO `contacts`;
UPDATE `categories` SET `section` = 'com_contacts' WHERE `section` = 'com_contact_details';

# --------------------------------------------------------

-- Update timezone offsets in user params.
UPDATE `users` SET `params` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`params`, 'timezone=-12', 'timezone=Etc/GMT-12'), 'timezone=-11', 'timezone=Pacific/Midway'), 'timezone=-10', 'timezone=Pacific/Honolulu'), 'timezone=-9.5', 'timezone=Pacific/Marquesas'), 'timezone=-9', 'timezone=US/Alaska'), 'timezone=-8', 'timezone=US/Pacific'), 'timezone=-7', 'timezone=US/Mountain'), 'timezone=-6', 'timezone=US/Central'), 'timezone=-5', 'timezone=US/Eastern'), 'timezone=-4.5', 'timezone=America/Caracas'), 'timezone=-4', 'timezone=America/Barbados'), 'timezone=-3.5', 'timezone=Canada/Newfoundland'), 'timezone=-3', 'timezone=America/Buenos_Aires'), 'timezone=-2', 'timezone=Atlantic/South_Georgia'), 'timezone=-1', 'timezone=Atlantic/Azores'), 'timezone=0', 'timezone=Europe/London'), 'timezone=1', 'timezone=Europe/Amsterdam'), 'timezone=2', 'timezone=Europe/Istanbul'), 'timezone=3', 'timezone=Asia/Riyadh'), 'timezone=3.5', 'timezone=Asia/Tehran'), 'timezone=4', 'timezone=Asia/Muscat'), 'timezone=4.5', 'timezone=Asia/Kabul'), 'timezone=5', 'timezone=Asia/Karachi'), 'timezone=5.5', 'timezone=Asia/Calcutta'), 'timezone=5.75', 'timezone=Asia/Katmandu'), 'timezone=6', 'timezone=Asia/Dhaka'), 'timezone=6.5', 'timezone=Indian/Cocos'), 'timezone=7', 'timezone=Asia/Bangkok'), 'timezone=8', 'timezone=Australia/Perth'), 'timezone=8.75', 'timezone=Australia/West'), 'timezone=9', 'timezone=Asia/Tokyo'), 'timezone=9.5', 'timezone=Australia/Adelaide'), 'timezone=10', 'timezone=Australia/Brisbane'), 'timezone=10.5', 'timezone=Australia/Lord_Howe'), 'timezone=11', 'timezone=Pacific/Kosrae'), 'timezone=11.5', 'timezone=Pacific/Norfolk'), 'timezone=12', 'timezone=Pacific/Auckland'), 'timezone=12.75', 'timezone=Pacific/Chatham'), 'timezone=13', 'timezone=Pacific/Tongatapu'), 'timezone=14', 'timezone=Pacific/Kiritimati');

-- Remove unused indexes
ALTER TABLE `users` DROP INDEX idx_name;
ALTER TABLE `users` DROP INDEX gid_block;

-- Update schema to follow conventions

-- Add UUID field required by identifiable behahvior
ALTER TABLE `users` ADD `uuid` CHAR(36) NOT NULL AFTER `id`;
UPDATE `users` SET `uuid` = UUID() WHERE `uuid` = '';
ALTER TABLE `users` ADD UNIQUE (`uuid`);
ALTER TABLE `users` CHANGE  `id`  `users_user_id` INT(11) UNSIGNED AUTO_INCREMENT;
ALTER TABLE `users` CHANGE  `block`  `enabled` TINYINT(1);
UPDATE `users` SET `enabled` = IF(`enabled`, 0, 1);
ALTER TABLE `users` CHANGE  `sendEmail`  `send_email` TINYINT(1);
ALTER TABLE `users` CHANGE  `gid`  `users_role_id` INT(11) UNSIGNED NOT NULL DEFAULT '18';
ALTER TABLE `users` ADD CONSTRAINT `users_user_role` FOREIGN KEY (`users_role_id`) REFERENCES `users_roles` (`users_role_id`);
ALTER TABLE `users` CHANGE  `registerDate`  `registered_on` DATETIME;
ALTER TABLE `users` CHANGE  `lastvisitDate`  `last_visited_on` DATETIME;

ALTER TABLE `users` ADD `created_by` INT(11) UNSIGNED AFTER `last_visited_on`;
ALTER TABLE `users` ADD `created_on` DATETIME AFTER `created_by`;
ALTER TABLE `users` ADD `modified_by` INT(11) UNSIGNED AFTER `created_on`;
ALTER TABLE `users` ADD `modified_on` DATETIME AFTER `modified_by`;
ALTER TABLE `users` ADD `locked_by` INT(11) UNSIGNED AFTER `modified_on`;
ALTER TABLE `users` ADD `locked_on` DATETIME AFTER `locked_by`;

ALTER TABLE `users` DROP `usertype`;
ALTER TABLE `users` DROP `registered_on`;

ALTER TABLE `users` MODIFY `enabled` tinyint(1) NOT NULL DEFAULT '1';

-- Add users_groups table
CREATE TABLE `users_groups` (
  `users_group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description`text NOT NULL,
  PRIMARY KEY (`users_group_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Add users_roles table
CREATE TABLE `users_roles` (
  `users_role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description`text NOT NULL,
  PRIMARY KEY (`users_role_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users_roles` (`users_role_id`, `name`, `description`)
VALUES
    (18, 'Registered', ''),
    (19, 'Author', ''),
    (20, 'Editor', ''),
    (21, 'Publisher', ''),
    (23, 'Manager', ''),
    (24, 'Administrator', ''),
    (25, 'Super Administrator', '');

RENAME TABLE`session` TO  `users_sessions`;

-- Remove unused columns from session
ALTER TABLE `users_sessions` DROP `username`;
ALTER TABLE `users_sessions` DROP `usertype`;
ALTER TABLE `users_sessions` DROP `gid`;
ALTER TABLE `users_sessions` CHANGE  `session_id`  `users_session_id` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `users_sessions` CHANGE  `userid`  `email` VARCHAR( 100 ) NOT NULL COMMENT  '@Filter("email")';
ALTER TABLE `users_sessions` DROP INDEX  `userid`;
ALTER TABLE `users_sessions` CHANGE  `client_id`  `application` VARCHAR( 50 ) NOT NULL;

ALTER TABLE `users` DROP `username`;

# --------------------------------------------------------

-- Upgrade modules rows
UPDATE `modules` SET `name` = 'mod_articles', `params` = CONCAT_WS('\n', 'show_content=1', `params`) WHERE `name` = 'mod_newsflash';
UPDATE `modules` SET `name` = 'mod_articles' WHERE `name` = 'mod_latestnews';
UPDATE `modules` SET `params` = REPLACE(REPLACE(REPLACE(`params`, 'catid', 'category'), 'secid', 'section'), 'items', 'count') WHERE `name` = 'mod_articles';
UPDATE `modules` SET `extensions_extension_id` = 20 WHERE `name` = 'mod_articles';

-- Rename tables to follow conventions
RENAME TABLE `content` TO `articles`;
DROP TABLE `content_frontpage`;

-- Clean trash
DELETE FROM `articles` WHERE `state` = '-2';

-- Update archived articles
UPDATE `articles` SET `state` = '0' WHERE `state` = '-1';

-- Update schema to follow conventions
ALTER TABLE `articles` CHANGE `id` `articles_article_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `articles` CHANGE  `catid`  `categories_category_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE `articles` DROP INDEX `idx_catid`;
ALTER TABLE `articles` ADD INDEX  `category` (  `categories_category_id` );
ALTER TABLE `articles` CHANGE  `metadesc`  `description` TEXT;
ALTER TABLE `articles` DROP INDEX `idx_checkout`;
ALTER TABLE `articles` ADD `attachments_attachment_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `categories_category_id`;

ALTER TABLE `articles` CHANGE  `attribs`  `params` TEXT;
ALTER TABLE `articles` CHANGE  `state`  `published` TINYINT(1);
ALTER TABLE `articles` CHANGE  `alias`  `slug` VARCHAR(250);
ALTER TABLE `articles` CHANGE  `created`  `created_on` DATETIME;
ALTER TABLE `articles` CHANGE  `modified`  `modified_on` DATETIME;
ALTER TABLE `articles` CHANGE  `checked_out`  `locked_by` INT(11) UNSIGNED;
ALTER TABLE `articles` CHANGE  `checked_out_time`  `locked_on` DATETIME;
ALTER TABLE `articles` CHANGE  `publish_up`  `publish_on` DATETIME;
ALTER TABLE `articles` CHANGE  `publish_down`  `unpublish_on` DATETIME;

UPDATE `articles` SET `categories_category_id` = NULL WHERE `categories_category_id` = 0;
UPDATE `articles` SET `created_by` = NULL WHERE `created_by` = 0;
UPDATE `articles` SET `created_on` = NULL WHERE `created_on` = '0000-00-00 00:00:00';
UPDATE `articles` SET `modified_by` = NULL WHERE `modified_by` = 0;
UPDATE `articles` SET `modified_on` = NULL WHERE `modified_on` = '0000-00-00 00:00:00';
UPDATE `articles` SET `locked_by` = NULL WHERE `locked_by` = 0;
UPDATE `articles` SET `locked_on` = NULL WHERE `locked_on` = '0000-00-00 00:00:00';
UPDATE `articles` SET `publish_on` = NULL WHERE `publish_on` = '0000-00-00 00:00:00';
UPDATE `articles` SET `unpublish_on` = NULL WHERE `unpublish_on` = '0000-00-00 00:00:00';
UPDATE `articles` SET `description` = NULL WHERE `description` = '';

-- Remove unused columns
ALTER TABLE `articles` DROP `title_alias`;
ALTER TABLE `articles` DROP `mask`;
ALTER TABLE `articles` DROP `images`;
ALTER TABLE `articles` DROP `urls`;
ALTER TABLE `articles` DROP `version`;
ALTER TABLE `articles` DROP `parentid`;
ALTER TABLE `articles` DROP `hits`;
ALTER TABLE `articles` DROP `sectionid`;
ALTER TABLE `articles` DROP `created_by_alias`;
ALTER TABLE `articles` DROP `metakey`;
ALTER TABLE `articles` DROP `metadata`;

# --------------------------------------------------------

-- Remove unused categories
DELETE FROM `categories` WHERE `section` = 'com_content';
DELETE FROM `categories` WHERE `section` = 'com_newsfeeds';
DELETE FROM `categories` WHERE `section` = 'com_banner';
DELETE FROM `categories` WHERE `section` = 'com_weblinks';

-- Remove unused columns
ALTER TABLE `categories` DROP `image_position`;
ALTER TABLE `categories` DROP `name`;
ALTER TABLE `categories` DROP `editor`;
ALTER TABLE `categories` DROP `image`;

-- Set parent_id of com_articles categories to the section
UPDATE `categories` SET `parent_id` = `section` , `section` = 'com_articles' WHERE `section` REGEXP '^-?[0-9]+$';

-- Remove the com_ prefix, the section now refers to the table
UPDATE `categories` SET `section` = REPLACE(`section`,'com_','');

-- Migrate date from sections to categories
ALTER TABLE `categories` ADD `old_id` int(11) NOT NULL;

INSERT INTO `categories` (`parent_id`, `title`, `alias`, `section`, `description`, `published`, `checked_out`, `checked_out_time`, `ordering`, `access`, `count`, `params`, `old_id`)
SELECT 0, title, alias, 'articles', description, published, checked_out, checked_out_time, ordering, access, count, params, id FROM sections;

UPDATE categories a, categories b SET a.parent_id = b.id WHERE b.old_id = a.parent_id AND a.parent_id != 0;
UPDATE menu a, categories b SET a.link = REPLACE(a.link, CONCAT('id=', b.old_id), CONCAT('id=', b.id)) WHERE `link` LIKE '%com_content%' AND `link` LIKE '%view=section%' AND `link` LIKE CONCAT('%id=', b.old_id ,'%');
ALTER TABLE categories DROP old_id;
DROP TABLE sections;

-- Update schema to follow conventions
ALTER TABLE `categories` CHANGE  `section` `table` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '';
ALTER TABLE `categories` DROP `count`;
ALTER TABLE `categories` CHANGE  `id`  `categories_category_id` INT( 11 ) NOT NULL AUTO_INCREMENT;

ALTER TABLE `categories` CHANGE  `alias`  `slug` VARCHAR(255);
ALTER TABLE `categories` ADD `created_by` INT(11) UNSIGNED AFTER `published`;
ALTER TABLE `categories` ADD `created_on` DATETIME AFTER `created_by`;
ALTER TABLE `categories` ADD `modified_by` INT(11) UNSIGNED AFTER `created_on`;
ALTER TABLE `categories` ADD `modified_on` DATETIME AFTER `modified_by`;
ALTER TABLE `categories` CHANGE `checked_out` `locked_by` INT UNSIGNED;
ALTER TABLE `categories` CHANGE `checked_out_time` `locked_on` DATETIME;
ALTER TABLE `categories` DROP INDEX `idx_checkout`;
ALTER TABLE `categories` ADD `attachments_attachment_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `categories_category_id`;

# --------------------------------------------------------

-- Remove com_newsfeeds
DROP TABLE `newsfeeds`;
DELETE FROM `components` WHERE `parent` = 11 OR `option` = 'com_newsfeeds';

-- Remove mod_feed
DELETE FROM `modules` WHERE `name` = 'mod_feed';

-- Remove menu links to newsfeeds component
DELETE FROM `menu` WHERE `componentid` = 11;

# --------------------------------------------------------

-- Remove com_wrapper
DELETE FROM `menu` WHERE `componentid` = 17;

# --------------------------------------------------------

-- Remove com_banners
DROP TABLE  `banner`;
DELETE FROM `components` WHERE `parent` = 1 OR `option` = 'com_banners';
DELETE FROM `modules` WHERE `name` = 'mod_banners';
DELETE FROM `menu` WHERE `componentid` = 1;

# --------------------------------------------------------

-- Remove com_weblinks
DROP TABLE  `weblinks`;
DELETE FROM `components` WHERE `parent` = 4 OR `option` = 'com_weblinks';
DELETE FROM `menu` WHERE `componentid` = 4;

# --------------------------------------------------------

-- Remove com_installer
DELETE FROM `components` WHERE `id` = 22;

# --------------------------------------------------------

-- Update schema to follow conventions
ALTER TABLE `contacts` CHANGE  `id`  `contacts_contact_id` INT( 11 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `contacts` DROP PRIMARY KEY , ADD PRIMARY KEY (  `contacts_contact_id` );

ALTER TABLE `contacts` CHANGE  `catid`  `categories_category_id` INT( 11 ) NOT NULL DEFAULT  '0';
ALTER TABLE `contacts` DROP INDEX  `catid` , ADD INDEX  `category` (  `categories_category_id` );

ALTER TABLE `contacts` CHANGE  `alias`  `slug` VARCHAR(255);
ALTER TABLE `contacts` CHANGE  `con_position`  `position` VARCHAR(255);

ALTER TABLE `contacts` CHANGE  `checked_out`  `locked_by` INT(11) UNSIGNED;
ALTER TABLE `contacts` CHANGE  `checked_out_time`  `locked_on` DATETIME;

ALTER TABLE `contacts` ADD `created_by` INT(11) UNSIGNED AFTER `published`;
ALTER TABLE `contacts` ADD `created_on` DATETIME AFTER `created_by`;

ALTER TABLE `contacts` ADD `modified_by` INT(11) UNSIGNED AFTER `created_on`;
ALTER TABLE `contacts` ADD `modified_on` DATETIME AFTER `modified_by`;

ALTER TABLE `contacts` DROP `imagepos`;
ALTER TABLE `contacts` DROP `default_con`;
ALTER TABLE `contacts` DROP `user_id`;
ALTER TABLE `contacts` DROP `webpage`;

# --------------------------------------------------------

--  Upgrade menu items links
UPDATE `menu` SET `link` = REPLACE(`link`, 'com_contact', 'com_contacts') WHERE `link` LIKE '%com_contact%';
UPDATE `menu` SET `link` = REPLACE(`link`, 'catid', 'category') WHERE `link` LIKE '%com_contact%';
UPDATE `menu` SET `link` = REPLACE(`link`, 'view=category', 'view=contacts') WHERE `link` LIKE '%com_contact%';
UPDATE `menu` SET `link` = REPLACE(`link`, 'com_content', 'com_articles') WHERE `link` LIKE '%com_content%';
UPDATE `menu` SET `link` = REPLACE(`link`, 'view=category&layout=blog', 'view=articles') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=category&layout=blog%';
UPDATE `menu` SET `link` = REPLACE(`link`, 'view=section&layout=blog', 'view=articles') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=section&layout=blog%';
UPDATE `menu` SET `link` = REPLACE(`link`, 'view=category', 'view=articles&layout=table') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=category%';
UPDATE `menu` SET `link` = REPLACE(`link`, 'view=section', 'view=categories') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=section%';
UPDATE `menu` SET `link` = REPLACE(`link`, 'id=', 'category=') WHERE `link` LIKE '%com_articles&view=categories%';
UPDATE `menu` SET `link` = REPLACE(`link`, 'id=', 'category=') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=articles%';
UPDATE `menu` SET `link` = REPLACE(`link`, '&layout=blog', '') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=articles%';
UPDATE `menu` SET `link` = REPLACE(`link`, 'view=frontpage', 'view=articles') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=frontpage%';

# --------------------------------------------------------

ALTER TABLE `articles` ENGINE = INNODB;
ALTER TABLE `categories` ENGINE = INNODB;
ALTER TABLE `components` ENGINE = INNODB;
ALTER TABLE `contacts` ENGINE = INNODB;
ALTER TABLE  `menu` ENGINE = INNODB;
ALTER TABLE  `menu_types` ENGINE = INNODB;
ALTER TABLE  `modules` ENGINE = INNODB;
ALTER TABLE  `modules_menu` ENGINE = INNODB;
ALTER TABLE  `users_sessions` ENGINE = INNODB;
ALTER TABLE  `users` ENGINE = INNODB;

# --------------------------------------------------------

DELETE FROM `components` WHERE `parent` > 0;

ALTER TABLE `components` DROP `iscore`;
ALTER TABLE `components` DROP `menuid`;
ALTER TABLE `components` DROP `admin_menu_img`;
ALTER TABLE `components` DROP `parent`;
ALTER TABLE `components` DROP `admin_menu_link`;
ALTER TABLE `components` DROP `admin_menu_alt`;
ALTER TABLE `components` DROP `ordering`;
ALTER TABLE `components` DROP `link`;
ALTER TABLE `components` CHANGE `id` `extensions_extension_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `components` CHANGE  `name`  `title` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '';
ALTER TABLE `components` CHANGE  `option`  `name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '';
ALTER TABLE `components` DROP INDEX `parent_option`;
ALTER TABLE `components` ADD UNIQUE (`name`);

RENAME TABLE `components` TO  `extensions`;

-- Rename components
UPDATE `extensions` SET `title` = 'Contacts', `name` = 'com_contacts' WHERE `extensions_extension_id` = 7;
UPDATE `extensions` SET `title` = 'Files', `name` = 'com_files' WHERE `extensions_extension_id` = 19;
UPDATE `extensions` SET `title` = 'Articles', `name` = 'com_articles' WHERE `extensions_extension_id` = 20;
UPDATE `extensions` SET `title` = 'Pages', `name` = 'com_pages' WHERE `extensions_extension_id` = 25;
UPDATE `extensions` SET `title` = 'Extensions', `name` = 'com_extensions' WHERE `extensions_extension_id` = 28;

-- Empty params field
UPDATE `extensions` SET `params` = '' WHERE `extensions_extension_id` = 7;
UPDATE `extensions` SET `params` = '' WHERE `extensions_extension_id` = 20;

# --------------------------------------------------------

INSERT INTO `extensions` (`extensions_extension_id`, `title`, `name`, `params`, `enabled`)
VALUES
    (NULL, 'Activities', 'com_activities', '', 1),
    (NULL, 'Dashboard', 'com_dashboard', '', 1);

# --------------------------------------------------------

-- Rename tables to follow conventions
RENAME TABLE `modules_menu` TO `pages_modules_pages`;
RENAME TABLE `menu` TO `pages`;
RENAME TABLE `menu_types` TO `pages_menus`;
RENAME TABLE `modules` TO  `pages_modules` ;

-- Update schema to follow conventions
ALTER TABLE `pages` CHANGE `id` `pages_page_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `pages` CHANGE `name` `title` VARCHAR(255) NOT NULL;
ALTER TABLE `pages` CHANGE `alias` `slug` VARCHAR(255);
ALTER TABLE `pages` CHANGE `link` `link_url` TEXT;
ALTER TABLE `pages` ADD COLUMN `link_id` INT(11) UNSIGNED AFTER `link_url`;
ALTER TABLE `pages` MODIFY `type` VARCHAR(50);
ALTER TABLE `pages` CHANGE `componentid` `extensions_extension_id` INT UNSIGNED;
ALTER TABLE `pages` CHANGE `checked_out` `locked_by` INT UNSIGNED;
ALTER TABLE `pages` CHANGE `checked_out_time` `locked_on` DATETIME;
ALTER TABLE `pages` ADD COLUMN `hidden` BOOLEAN NOT NULL DEFAULT 0 AFTER `published`;
ALTER TABLE `pages` MODIFY `home` BOOLEAN NOT NULL DEFAULT 0 AFTER `hidden`;

ALTER TABLE `pages` ADD `created_by` INT(11) UNSIGNED AFTER `extensions_extension_id`;
ALTER TABLE `pages` ADD `created_on` DATETIME AFTER `created_by`;
ALTER TABLE `pages` ADD `modified_by` INT(11) UNSIGNED AFTER `created_on`;
ALTER TABLE `pages` ADD `modified_on` DATETIME AFTER `modified_by`;

UPDATE `pages` SET `link_url` = SUBSTRING(`link_url`, 11) WHERE `link_url` LIKE 'index.php?%';

ALTER TABLE `pages_menus` ADD `application` VARCHAR(50) NOT NULL AFTER `id`;

ALTER TABLE `pages_menus` CHANGE `id` `pages_menu_id` INT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `pages_menus` CHANGE `menutype` `slug` VARCHAR(255) AFTER `title`;
ALTER TABLE `pages_menus` MODIFY `title` VARCHAR(255) NOT NULL;
ALTER TABLE `pages_menus` MODIFY `description` VARCHAR(255);

ALTER TABLE `pages_menus` ADD `created_by` INT UNSIGNED AFTER `description`;
ALTER TABLE `pages_menus` ADD `created_on` DATETIME AFTER `created_by`;
ALTER TABLE `pages_menus` ADD `modified_by` INT UNSIGNED AFTER `created_on`;
ALTER TABLE `pages_menus` ADD `modified_on` DATETIME AFTER `modified_by`;
ALTER TABLE `pages_menus` ADD `locked_by` INT UNSIGNED AFTER `modified_on`;
ALTER TABLE `pages_menus` ADD `locked_on` DATETIME AFTER `locked_by`;

INSERT INTO `pages_menus` (`pages_menu_id`, `application`, `title`, `slug`, `description`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`)
VALUES
    (NULL, 'admin', 'Menubar', 'menubar', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

ALTER TABLE `pages_modules` CHANGE `id` `pages_module_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `pages_modules` ADD `created_by` INT(11) UNSIGNED AFTER `position`;
ALTER TABLE `pages_modules` ADD `created_on` DATETIME AFTER `created_by`;
ALTER TABLE `pages_modules` ADD `modified_by` INT(11) UNSIGNED AFTER `created_on`;
ALTER TABLE `pages_modules` ADD `modified_on` DATETIME AFTER `modified_by`;
ALTER TABLE `pages_modules` CHANGE `checked_out` `locked_by` INT UNSIGNED;
ALTER TABLE `pages_modules` CHANGE `checked_out_time` `locked_on` DATETIME;

ALTER TABLE `pages_modules_pages` CHANGE `moduleid` `pages_module_id` INT(11) UNSIGNED NOT NULL;
ALTER TABLE `pages_modules_pages` CHANGE `menuid` `pages_page_id` INT(11) UNSIGNED NOT NULL;

ALTER TABLE `pages_modules_pages` ADD CONSTRAINT `pages_modules_pages__pages_module_id` FOREIGN KEY (`pages_module_id`) REFERENCES `pages_modules` (`pages_module_id`) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE `pages_modules_pages` ADD CONSTRAINT `pages_modules_pages__pages_page_id` FOREIGN KEY (`pages_page_id`) REFERENCES `pages` (`pages_page_id`) ON UPDATE CASCADE ON DELETE CASCADE;

-- Add admin menubar pages
INSERT INTO `pages` (`menutype`, `title`, `slug`, `link_url`, `type`, `published`, `extensions_extension_id`, `ordering`, `parent`, `sublevel`, `params`)
  VALUES
  ('menubar', 'Dashboard', 'dashboard', 'option=com_dashboard&view=dashboard', 'component', 1, (SELECT `extensions_extension_id` FROM `extensions` WHERE `name` = 'com_dashboard'), 1, 0, 0, '');

SET @base = LAST_INSERT_ID();

INSERT INTO `pages` (`menutype`, `title`, `slug`, `link_url`, `type`, `published`, `extensions_extension_id`, `ordering`, `parent`, `sublevel`, `params`)
  VALUES
  ('menubar', 'Pages', 'pages', 'option=com_pages&view=pages', 'component', 1, 25, 2, 0, 0, ''),
  ('menubar', 'Content', 'content', NULL, 'separator', 1, NULL, 3, 0, 0, ''),
  ('menubar', 'Files', 'files', 'option=com_files&view=files', 'component', 1, 19, 4, 0, 0, ''),
  ('menubar', 'Users', 'users', 'option=com_users&view=users', 'component', 1, 31, 5, 0, 0, ''),
  ('menubar', 'Extensions', 'extensions', NULL, 'separator', 1, NULL, 6, 0, 0, ''),
  ('menubar', 'Settings', 'settings', 'option=com_extensions&view=settings', 'component', 1, 28, 1, @base + 5, 1, ''),
  ('menubar', 'Tools', 'tools', NULL, 'separator', 1, NULL, 7, 0, 0, ''),
  ('menubar', 'Activity Logs', 'activity-logs', 'option=com_activities&view=activities', 'component', 1, (SELECT `extensions_extension_id` FROM `extensions` WHERE `name` = 'com_activities'), 1, @base + 7, 1, ''),
  ('menubar', 'Clean Cache', 'clean-cache', 'option=com_cache&view=items', 'component', 1, 32, 2, @base + 7, 1, ''),
  ('menubar', 'Articles', 'articles', 'option=com_articles&view=articles', 'component', 1, 20, 1, @base + 2, 1, ''),
  ('menubar', 'Contacts', 'contacts', 'option=com_contacts&view=contacts', 'component', 1, 7, 3, @base + 2, 1, ''),
  ('menubar', 'Languages', 'languages', 'option=com_languages&view=languages', 'component', 1, 23, 4, @base + 2, 1, ''),
  ('menubar', 'Articles', 'articles', 'option=com_articles&view=articles', 'component', 1, 20, 1, @base + 10, 2, ''),
  ('menubar', 'Categories', 'categories', 'option=com_articles&view=categories', 'component', 1, 20, 2, @base + 10, 2, ''),
  ('menubar', 'Contacts', 'contacts', 'option=com_contacts&view=contacts', 'component', 1, 7, 1, @base + 12, 2, ''),
  ('menubar', 'Categories', 'categories', 'option=com_contacts&view=categories', 'component', 1, 7, 2, @base + 12, 2, ''),
  ('menubar', 'Languages', 'languages', 'option=com_languages&view=languages', 'component', 1, 23, 1, @base + 13, 2, ''),
  ('menubar', 'Extensions', 'extensions', 'option=com_languages&view=extensions', 'component', 1, 23, 2, @base + 13, 2, ''),
  ('menubar', 'Pages', 'pages', 'option=com_pages&view=pages', 'component', 1, 25, 1, @base + 1, 1, ''),
  ('menubar', 'Menus', 'menus', 'option=com_pages&view=menus', 'component', 1, 25, 2, @base + 1, 1, ''),
  ('menubar', 'Modules', 'modules', 'option=com_pages&view=modules', 'component', 1, 25, 3, @base + 1, 1, ''),
  ('menubar', 'Users', 'users', 'option=com_users&view=users', 'component', 1, 31, 1, @base + 4, 1, ''),
  ('menubar', 'Groups', 'groups', 'option=com_users&view=groups', 'component', 1, 31, 2, @base + 4, 1, ''),
  ('menubar', 'Items', 'items', 'option=com_cache&view=items', 'component', 1, 32, 1, @base + 9, 2, ''),
  ('menubar', 'Groups', 'groups', 'option=com_cache&view=groups', 'component', 1, 32, 2, @base + 9, 2, '');

ALTER TABLE `pages` ADD COLUMN `pages_menu_id` INT UNSIGNED NOT NULL AFTER `pages_page_id`;
UPDATE `pages` AS `pages`, `pages_menus` AS `menus` SET `pages`.`pages_menu_id` = `menus`.`pages_menu_id` WHERE `menus`.`slug` = `pages`.`menutype`;

ALTER TABLE `pages` ADD COLUMN `users_group_id` INT UNSIGNED AFTER `pages_menu_id`;

ALTER TABLE `pages` DROP INDEX `componentid`;
ALTER TABLE `pages` ADD INDEX `ix_published` (`published`);
ALTER TABLE `pages` ADD INDEX `ix_extensions_extension_id` (`extensions_extension_id`);
ALTER TABLE `pages` ADD INDEX `ix_home` (`home`);
#ALTER TABLE `pages` ADD CONSTRAINT `pages__pages_menu_id` FOREIGN KEY (`pages_menu_id`) REFERENCES `pages_menus` (`pages_menu_id`) ON DELETE CASCADE;
ALTER TABLE `pages` ADD CONSTRAINT `pages__link_id` FOREIGN KEY (`link_id`) REFERENCES `pages` (`pages_page_id`) ON DELETE CASCADE;

UPDATE `pages_modules` SET `name` = 'mod_menu' WHERE `name` = 'mod_mainmenu';
UPDATE `pages_modules` AS `modules` SET `modules`.`params` = REPLACE(`modules`.`params`, CONCAT('menutype=', SUBSTRING_INDEX(SUBSTRING_INDEX(`modules`.`params`, 'menutype=', -1), '\n', 1)), CONCAT('menu_id=', (SELECT `pages_menu_id` FROM `pages_menus` AS `menus` WHERE `menus`.`slug` = SUBSTRING_INDEX(SUBSTRING_INDEX(`modules`.`params`, 'menutype=', -1), '\n', 1)))) WHERE `modules`.`name` = 'mod_menu';

UPDATE `pages` SET `params` = REPLACE(`params`, 'menu_item=', 'page_id=');
UPDATE `pages` SET `link_id` = SUBSTRING(`link_url`, LOCATE('Itemid=', `link_url`) + 7) WHERE `type` = 'menulink';
UPDATE `pages` SET `type` = 'pagelink' WHERE `type` = 'menulink';

UPDATE `pages_menus` SET `application` = 'site' WHERE `application` = '';

-- Clean trash
DELETE FROM `pages` WHERE `published` < 0;

-- Add relations table
CREATE TABLE IF NOT EXISTS `pages_closures` (
    `ancestor_id` INT(11) UNSIGNED NOT NULL,
    `descendant_id` INT(11) UNSIGNED NOT NULL,
    `level` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`ancestor_id`, `descendant_id`),
    CONSTRAINT `pages_closures__ancestor_id` FOREIGN KEY (`ancestor_id`) REFERENCES `pages` (`pages_page_id`) ON DELETE CASCADE,
    CONSTRAINT `pages_closures__descendant_id` FOREIGN KEY (`descendant_id`) REFERENCES `pages` (`pages_page_id`) ON DELETE CASCADE,
    INDEX `ix_level` (`level`),
    INDEX `ix_descendant_id` (`descendant_id`)
) ENGINE = InnoDB CHARSET = utf8;

-- Convert adjacency hierarchy to closure
DROP PROCEDURE IF EXISTS `convert_adjacency_to_closure`;

DELIMITER //
CREATE PROCEDURE convert_adjacency_to_closure()
BEGIN
    DECLARE distance TINYINT UNSIGNED DEFAULT 0;
    DECLARE max_level TINYINT UNSIGNED;
    SELECT MAX(`sublevel`) INTO max_level FROM `pages`;

    TRUNCATE `pages_closures`;
    INSERT INTO `pages_closures` SELECT `pages_page_id`, `pages_page_id`, 0 FROM `pages`;

    WHILE distance < max_level DO
        INSERT INTO `pages_closures`
            SELECT `relations`.`ancestor_id`, `pages`.`pages_page_id`, distance + 1
            FROM `pages_closures` AS `relations`, `pages` AS `pages`
            WHERE `relations`.`descendant_id` = `pages`.`parent` AND `relations`.`level` = distance;

        SET distance = distance + 1;
    END WHILE;
END//
DELIMITER ;

CALL convert_adjacency_to_closure();
DROP PROCEDURE convert_adjacency_to_closure;

-- Add orderings table
CREATE TABLE `pages_orderings` (
  `pages_page_id` int(11) unsigned NOT NULL,
  `title` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  `custom` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  PRIMARY KEY (`pages_page_id`),
  KEY `ix_title` (`title`),
  KEY `ix_custom` (`custom`),
  CONSTRAINT `pages_orderings__pages_page_id` FOREIGN KEY (`pages_page_id`) REFERENCES `pages` (`pages_page_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Populate id and custom columns
INSERT INTO `pages_orderings` (`pages_page_id`, `custom`) SELECT `pages_page_id`, `ordering` AS `custom` FROM `pages`;

-- Populate title column
DROP PROCEDURE IF EXISTS `populate_ordering_title`;

DELIMITER //
CREATE PROCEDURE populate_ordering_title()
BEGIN
    DECLARE menu_id INT;
    DECLARE distance TINYINT UNSIGNED DEFAULT 0;
    DECLARE max_level TINYINT UNSIGNED;
    DECLARE done BOOLEAN DEFAULT FALSE;
    DECLARE menu_cursor CURSOR FOR SELECT DISTINCT `pages_menu_id` FROM `pages`;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN menu_cursor;
    menu_loop: LOOP
        FETCH menu_cursor INTO menu_id;

        IF done THEN
            LEAVE menu_loop;
        END IF;

        SELECT MAX(`sublevel`) INTO max_level FROM `pages` WHERE `pages_menu_id` = menu_id;
        SET distance = 0;

        WHILE distance <= max_level DO
            SET @index = 1, @parent_id = -1;
            UPDATE `pages_orderings` AS `orderings`, (SELECT `pages_page_id`, @index := IF(@parent_id = `parent`, @index + 1, 1) AS `index`, @parent_id := `parent` FROM `pages` WHERE `pages_menu_id` = menu_id AND `sublevel` = distance ORDER BY `parent`, `title` ASC) AS `pages`
                SET `orderings`.`title` = `index` WHERE `orderings`.`pages_page_id` = `pages`.`pages_page_id`;

            SET distance = distance + 1;
        END WHILE;
    END LOOP;
    CLOSE menu_cursor;
END//
DELIMITER ;

CALL populate_ordering_title();
DROP PROCEDURE populate_ordering_title;

-- Drop unnecessary columns
ALTER TABLE `pages` DROP COLUMN `menutype`;
ALTER TABLE `pages` DROP COLUMN `parent`;
ALTER TABLE `pages` DROP COLUMN `sublevel`;
ALTER TABLE `pages` DROP COLUMN `ordering`;
ALTER TABLE `pages` DROP COLUMN `pollid`;
ALTER TABLE `pages` DROP COLUMN `browserNav`;
ALTER TABLE `pages` DROP COLUMN `utaccess`;
ALTER TABLE `pages` DROP COLUMN `lft`;
ALTER TABLE `pages` DROP COLUMN `rgt`;

# --------------------------------------------------------

CREATE TABLE `users_groups_users` (
  `users_group_id` int(11) unsigned NOT NULL,
  `users_user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`users_group_id`,`users_user_id`),
  KEY `jos_users_groups_users__users_user_id` (`users_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users_groups_users` ADD CONSTRAINT `users_groups_users__users_user_id` FOREIGN KEY (`users_user_id`) REFERENCES `users` (`users_user_id`) ON DELETE CASCADE;
ALTER TABLE `users_groups_users` ADD CONSTRAINT `users_groups_users__users_group_id` FOREIGN KEY (`users_group_id`) REFERENCES `users_groups` (`users_group_id`) ON DELETE CASCADE;

CREATE TABLE `users_passwords` (
  `email` varchar(100) NOT NULL DEFAULT '',
  `expiration` date DEFAULT NULL,
  `hash` varchar(100) NOT NULL DEFAULT '',
  `reset` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`email`),
  CONSTRAINT `users_password__email` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users_passwords` (`email`, `expiration`, `hash`, `reset`) SELECT `email`, NULL, `password`, '' FROM `users`;

ALTER TABLE `users` DROP COLUMN `password`;

# --------------------------------------------------------

DROP TABLE `core_acl_aro`;
DROP TABLE `core_acl_aro_groups`;
DROP TABLE `core_acl_aro_map`;
DROP TABLE `core_acl_aro_sections`;
DROP TABLE `core_acl_groups_aro_map`;

# --------------------------------------------------------

-- Remove access level 'special' by changing these items to unpublished registered status
UPDATE `articles`      SET `access` = '1', `published` = '0' WHERE `access` = '2';
UPDATE `categories`    SET `access` = '1', `published` = '0' WHERE `access` = '2';
UPDATE `contacts`      SET `access` = '1', `published` = '0' WHERE `access` = '2';
UPDATE `pages_modules` SET `access` = '1', `published` = '0' WHERE `access` = '2';
UPDATE `pages`         SET `access` = '1', `published` = '0' WHERE `access` = '2';

# --------------------------------------------------------

-- Add tables
CREATE TABLE `languages` (
    `languages_language_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `application` VARCHAR(50) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `native_name` VARCHAR(150) NOT NULL,
    `iso_code` VARCHAR(8) NOT NULL,
    `slug` VARCHAR(50) NOT NULL,
    `enabled` BOOLEAN NOT NULL DEFAULT 0,
    `primary` BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (`languages_language_id`)
) ENGINE = InnoDB CHARSET = utf8;

CREATE TABLE `languages_translations` (
    `languages_translation_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `iso_code` VARCHAR(8) NOT NULL,
    `table` VARCHAR(64) NOT NULL,
    `row` INT UNSIGNED NOT NULL,
    `status` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `original` BOOLEAN NOT NULL DEFAULT 0,
    `deleted` BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (`languages_translation_id`),
    KEY `table_row_iso_code` (`table`, `row`, `iso_code`)
) ENGINE = InnoDB CHARSET = utf8;

CREATE TABLE `languages_tables` (
    `languages_table_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `extensions_extension_id` INT UNSIGNED,
    `name` VARCHAR(64) NOT NULL,
    `unique_column` VARCHAR(64) NOT NULL,
    `enabled` BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (`languages_table_id`)
    # CONSTRAINT `languages_tables__extensions_extension_id` FOREIGN KEY (`extensions_extension_id`) REFERENCES `extensions` (`extensions_extension_id`) ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8;

-- Add primary languages
INSERT INTO `languages` (`languages_language_id`, `application`, `name`, `native_name`, `iso_code`, `slug`, `enabled`, `primary`)
VALUES
    (1, 'admin', 'English (United Kingdom)', 'English (United Kingdom)', 'en-GB', 'en', 1, 1),
    (2, 'site', 'English (United Kingdom)', 'English (United Kingdom)', 'en-GB', 'en', 1, 1);

-- Add tables
INSERT INTO `languages_tables` (`extensions_extension_id`, `name`, `unique_column`, `enabled`)
VALUES
    (20, 'articles', 'articles_article_id', 0),
    (20, 'categories', 'categories_category_id', 0);

-- Add attachments support
CREATE TABLE `attachments` (
  `attachments_attachment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `container` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `description` text,
  `created_by` int(11) unsigned DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(11) unsigned DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `locked_by` int(11) unsigned DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  PRIMARY KEY (`attachments_attachment_id`),
  KEY `path` (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `attachments_relations` (
  `attachments_attachment_id` int(10) unsigned NOT NULL,
  `table` varchar(64) NOT NULL,
  `row` int(10) unsigned NOT NULL,
  KEY `attachments_attachment_id` (`attachments_attachment_id`),
  CONSTRAINT `attachments_relations_ibfk_1` FOREIGN KEY (`attachments_attachment_id`) REFERENCES `attachments` (`attachments_attachment_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `files_containers` (`files_container_id`, `slug`, `title`, `path`, `parameters`)
VALUES
	(NULL, 'attachments-attachments', 'Attachments', 'attachments', '{\"thumbnails\": true,\"maximum_size\":\"10485760\",\"allowed_extensions\": [\"bmp\", \"csv\", \"doc\", \"gif\", \"ico\", \"jpg\", \"jpeg\", \"odg\", \"odp\", \"ods\", \"odt\", \"pdf\", \"png\", \"ppt\", \"sql\", \"swf\", \"txt\", \"xcf\", \"xls\"],\"allowed_mimetypes\": [\"image/jpeg\", \"image/gif\", \"image/png\", \"image/bmp\", \"application/x-shockwave-flash\", \"application/msword\", \"application/excel\", \"application/pdf\", \"application/powerpoint\", \"text/plain\", \"application/x-zip\"]}');


-- Add tag support
CREATE TABLE `tags` (
  `tags_tag_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `table` varchar(50) NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `locked_by` int(10) unsigned DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`tags_tag_id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `title` (`title`),
  KEY `table` (`table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tags_relations` (
  `tags_tag_id` BIGINT(20) UNSIGNED NOT NULL,
  `row` BIGINT(20) UNSIGNED NOT NULL,
  `table` VARCHAR( 255 ) NOT NULL,
  PRIMARY KEY  (`tags_tag_id`,`row`,`table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Improve files_thumbnails
ALTER TABLE `files_thumbnails` ADD INDEX (`filename`);