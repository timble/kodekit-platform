# --------------------------------------------------------
# Removing unused extensions

-- Remove legacy plugin
-- http://nooku.assembla.com/spaces/nooku-server/tickets/191-remove-joomla-legacy-support
DELETE FROM `#__plugins` WHERE `id` = 29;

-- Remove Joomla user plugin
DELETE FROM `#__plugins` WHERE `id` = 5;

-- Remove Joomla authentication plugin
DELETE FROM `#__plugins` WHERE `id` = 1;

-- Remove editor and editor-xtd plugins
DELETE FROM `#__plugins` WHERE `folder` = 'editors' OR `folder` = 'editors-xtd';

-- Remove section search plugin
DELETE FROM `#__plugins` WHERE `folder` = 'search' AND `element` = 'sections';

-- Remove administrator latest news module
-- http://nooku.assembla.com/spaces/nooku-server/tickets/217-remove-administrator-latest-news-module
DELETE FROM `#__modules` WHERE `id` = 4;

-- Remove mod_related_items
DELETE FROM `#__modules` WHERE `module` = 'mod_related_items';s

-- Remove core logs
DROP TABLE `#__core_log_items`, `#__core_log_searches`;

-- Remove messages functionality
DROP TABLE `#__messages`, `#__messages_cfg`;

-- Remove unused tables
DROP TABLE #__stats_agents;
DROP TABLE #__migration_backlinks
DROP TABLE #__groups
DROP TABLE #__templates_menu

-- Remove components
DELETE FROM `#__components` WHERE `option` = 'com_wrapper';
DELETE FROM `#__components` WHERE `option` = 'com_massmail';
DELETE FROM `#__components` WHERE `option` = 'com_mailto';
DELETE FROM `#__components` WHERE `option` = 'com_templates';
DELETE FROM `#__components` WHERE `option` = 'com_messages';

# --------------------------------------------------------
# com_contacts schema changes

-- Rename contacts_details to contacts_contacts
RENAME TABLE  `#__contact_details` TO `#__contacts`;
UPDATE `#__categories` SET `section` = 'com_contacts' WHERE `section` = 'com_contact_details';

# --------------------------------------------------------
# com_users schema changes

-- Update timezone offsets in user params.
UPDATE `#__users` SET `params` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`params`, 'timezone=-12', 'timezone=Etc/GMT-12'), 'timezone=-11', 'timezone=Pacific/Midway'), 'timezone=-10', 'timezone=Pacific/Honolulu'), 'timezone=-9.5', 'timezone=Pacific/Marquesas'), 'timezone=-9', 'timezone=US/Alaska'), 'timezone=-8', 'timezone=US/Pacific'), 'timezone=-7', 'timezone=US/Mountain'), 'timezone=-6', 'timezone=US/Central'), 'timezone=-5', 'timezone=US/Eastern'), 'timezone=-4.5', 'timezone=America/Caracas'), 'timezone=-4', 'timezone=America/Barbados'), 'timezone=-3.5', 'timezone=Canada/Newfoundland'), 'timezone=-3', 'timezone=America/Buenos_Aires'), 'timezone=-2', 'timezone=Atlantic/South_Georgia'), 'timezone=-1', 'timezone=Atlantic/Azores'), 'timezone=0', 'timezone=Europe/London'), 'timezone=1', 'timezone=Europe/Amsterdam'), 'timezone=2', 'timezone=Europe/Istanbul'), 'timezone=3', 'timezone=Asia/Riyadh'), 'timezone=3.5', 'timezone=Asia/Tehran'), 'timezone=4', 'timezone=Asia/Muscat'), 'timezone=4.5', 'timezone=Asia/Kabul'), 'timezone=5', 'timezone=Asia/Karachi'), 'timezone=5.5', 'timezone=Asia/Calcutta'), 'timezone=5.75', 'timezone=Asia/Katmandu'), 'timezone=6', 'timezone=Asia/Dhaka'), 'timezone=6.5', 'timezone=Indian/Cocos'), 'timezone=7', 'timezone=Asia/Bangkok'), 'timezone=8', 'timezone=Australia/Perth'), 'timezone=8.75', 'timezone=Australia/West'), 'timezone=9', 'timezone=Asia/Tokyo'), 'timezone=9.5', 'timezone=Australia/Adelaide'), 'timezone=10', 'timezone=Australia/Brisbane'), 'timezone=10.5', 'timezone=Australia/Lord_Howe'), 'timezone=11', 'timezone=Pacific/Kosrae'), 'timezone=11.5', 'timezone=Pacific/Norfolk'), 'timezone=12', 'timezone=Pacific/Auckland'), 'timezone=12.75', 'timezone=Pacific/Chatham'), 'timezone=13', 'timezone=Pacific/Tongatapu'), 'timezone=14', 'timezone=Pacific/Kiritimati');

-- Remove unused indexes
ALTER TABLE jos_users DROP INDEX idx_name;
ALTER TABLE jos_users DROP INDEX gid_block;

-- Update indexes
ALTER TABLE  `jos_users` DROP INDEX  `email` , ADD UNIQUE  `email` (  `email` );

-- Remove unused columns from #__session
RENAME TABLE`#__session` TO  `#__users_sessions`;

ALTER TABLE `#__users_sessions` DROP `username`;
ALTER TABLE `#__users_sessions` DROP `usertype`;
ALTER TABLE `#__users_sessions` DROP `gid`;
ALTER TABLE `#__users_sessions` CHANGE  `session_id`  `users_session_id` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `#__users_sessions` CHANGE  `userid`  `email` VARCHAR( 100 ) NOT NULL COMMENT  '@Filter("email")'
ALTER TABLE `#__users_sessions` DROP INDEX  `userid`;

ALTER TABLE `#__users` DROP `username`;

# --------------------------------------------------------
# com_content schema changes

-- Upgrade modules rows
UPDATE `#__modules` SET `module` = 'mod_articles', `params` = CONCAT_WS('\n', 'show_content=1', `params`) WHERE `module` = 'mod_newsflash';
UPDATE `#__modules` SET `module` = 'mod_articles' WHERE `module` = 'mod_latestnews';
UPDATE `#__modules` SET `params` = REPLACE(REPLACE(REPLACE(REPLACE(`params`, 'catid', 'category'), 'secid', 'section'), 'show_front', 'show_featured'), 'items', 'count') WHERE `module` = 'mod_articles';

-- Rename tables to follow conventions
RENAME TABLE `#__content` TO `#__articles`;
RENAME TABLE `#__content_frontpage` TO `#__articles_featured`;

-- Update schema to follow conventions
ALTER TABLE `#__articles` CHANGE `id` `articles_article_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__articles_featured` CHANGE `content_id` `articles_article_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `#__articles` CHANGE  `catid`  `categories_category_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE `#__articles` DROP INDEX `idx_catid` ADD INDEX  `category` (  `categories_category_id` );

-- Remove unused columns
ALTER TABLE `#__articles` DROP `title_alias`;
ALTER TABLE `#__articles` DROP `mask`;
ALTER TABLE `#__articles` DROP `images`;
ALTER TABLE `#__articles` DROP `urls`;
ALTER TABLE `#__articles` DROP `version`;
ALTER TABLE `#__articles` DROP `parentid`;
ALTER TABLE `#__articles` DROP `hits`
ALTER TABLE `#__articles` DROP `sectionid`

-- Remove loadmodule plugin
DELETE FROM `#__plugins` WHERE `element` = 'loadmodule' AND `folder` = 'content';

-- Remove pagenavigation plugin
DELETE FROM `#__plugins` WHERE `element` = 'pagenavigation' AND `folder` = 'content';

-- Remove unused table
DROP TABLE #__content_rating;

# --------------------------------------------------------
# com_categories schema changes

-- Remove unused categories
DELETE FROM `#__categories` WHERE `section` = 'com_content';
DELETE FROM `#__categories` WHERE `section` = 'com_newsfeeds';
DELETE FROM `#__categories` WHERE `section` = 'com_banner';

-- Set parent_id of com_articles categories to the section
UPDATE `#__categories` SET `parent_id` = `section` , `section` = 'com_articles' WHERE `section` > 0;

-- Remove the com_ prefix, the section now refers to the table
UPDATE `#__categories` SET `section` = REPLACE(`section`,'com_','');

-- Migrate date from sections to categories
ALTER TABLE #__categories ADD old_id int(11) NOT NULL;
INSERT INTO #__categories (parent_id, title, alias, image, `table`, description, published, checked_out, checked_out_time, ordering, access, count, params, old_id)
SELECT 0, title, alias, image, 'articles', description, published, checked_out, checked_out_time, ordering, access, count, params, id FROM #__sections;
UPDATE #__categories a, #__categories b SET a.parent_id = b.id WHERE b.old_id = a.parent_id AND a.parent_id != 0
UPDATE #__menu a, #__categories b SET a.link = REPLACE(a.link, CONCAT('id=', b.old_id), CONCAT('id=', b.id)) WHERE `link` LIKE '%com_content%' AND `link` LIKE '%view=section%' AND `link` LIKE CONCAT('%id=', b.old_id ,'%');
ALTER TABLE #__categories DROP old_id;
DROP TABLE #__sections;

-- Update schema to follow conventions
ALTER TABLE `#__categories` CHANGE  `section` `table` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '';
ALTER TABLE `#__categories` DROP `count`;
ALTER TABLE `#__categories` CHANGE  `id`  `categories_category_id` INT( 11 ) NOT NULL AUTO_INCREMENT;

# --------------------------------------------------------
# com_newsfeeds schema changes

-- Remove com_newsfeeds
DROP TABLE `#__newsfeeds`;
DELETE FROM `#__components` WHERE `parent` = 11 OR `option` = 'com_newsfeeds';

-- Remove mod_feed
DELETE FROM `#__modules` WHERE `module` = 'mod_feed';

-- Remove newsfeeds search plugin
DELETE FROM `#__plugins` WHERE `element` = 'newsfeeds' AND `folder` = 'search';

-- Remove menu links to newsfeeds component
DELETE FROM `#__menu` WHERE `componentid` = 11;

# --------------------------------------------------------
# com_banners schema changes

-- Remove com_banners
DROP TABLE `#__banner`, `#__bannerclient`, `#__bannertrack`;
DELETE FROM `#__components` WHERE `parent` = 1 OR `option` = 'com_banners';

-- Remove mod_feed
DELETE FROM `#__modules` WHERE `module` = 'mod_banners';

-- Remove menu links to banners component
DELETE FROM `#__menu` WHERE `componentid` = 1;

# --------------------------------------------------------
# com_polls schema changes

DELETE FROM `#__components` WHERE `option` = 'com_poll';
DROP TABLE `#__polls`, `#__poll_data`, `#__poll_date`, `#__poll_menu`;

DELETE FROM `#__modules` WHERE `module` = 'mod_poll';

# --------------------------------------------------------
# com_installer schema changes

-- Remove com_installer
DELETE FROM `#__components` WHERE `id` = 22

# --------------------------------------------------------
# plugin schema changes

DELETE FROM `#__plugins` WHERE `folder` = 'authentication';
DELETE FROM `#__plugins` WHERE `folder` = 'content';
DELETE FROM `#__plugins` WHERE `folder` = 'xmlrpc';

DELETE FROM `#__plugins` WHERE `element` = 'mtupgrade';
DELETE FROM `#__plugins` WHERE `element` = 'backlink';
DELETE FROM `#__plugins` WHERE `element` = 'remember';

UPDATE  `#__plugins` SET  `name` =  'System - Route', `element` =  'route' WHERE  `id` = 27;

# --------------------------------------------------------
# com_categories schema changes

-- Remove unused columns
ALTER TABLE `#__categories` DROP `image_position`;
ALTER TABLE `#__categories` DROP `name`;
ALTER TABLE `#__categories` DROP `editor`;

# --------------------------------------------------------
# com_weblinks schema changes

-- Remove unused columns
ALTER TABLE `#__weblinks` DROP `sid`;
ALTER TABLE `#__weblinks` DROP `archived`;
ALTER TABLE `#__weblinks` DROP `approved`;

-- Remove weblink submission links
DELETE FROM `#__menu` WHERE `link` = 'index.php?option=com_weblinks&view=weblink&layout=form';

-- Update components table
UPDATE `#__components` SET `link` = 'option=com_weblinks&view=categories' WHERE `link` = 'option=com_categories&section=com_weblinks';

-- Update schema to follow conventions
ALTER TABLE  `#__weblinks` CHANGE  `id`  `weblinks_weblink_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE  `#__weblinks` DROP PRIMARY KEY , ADD PRIMARY KEY (  `weblinks_weblink_id` );

ALTER TABLE  `#__weblinks` CHANGE  `catid`  `categories_category_id` INT( 11 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `#__weblinks` DROP INDEX  `catid` , ADD INDEX  `category` (  `categories_category_id` );

# --------------------------------------------------------
# com_contacts schema changes

-- Update schema to follow conventions
ALTER TABLE  `#__contacts` CHANGE  `id`  `contacts_contact_id` INT( 11 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE  `#__contacts` DROP PRIMARY KEY , ADD PRIMARY KEY (  `contacts_contact_id` );

ALTER TABLE  `#__contacts` CHANGE  `catid`  `categories_category_id` INT( 11 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `#__contacts` DROP INDEX  `catid` , ADD INDEX  `category` (  `categories_category_id` );

ALTER TABLE `jos_contacts` DROP `imagepos`;

-- Update components table
UPDATE `#__components` SET `link` = 'option=com_contacts&view=categories' WHERE `link` = 'option=com_categories&section=com_contact_details';

# --------------------------------------------------------
# com_pages schema changes

--  Upgrade menu items links
UPDATE `#__menu` SET `link` = REPLACE(`link`, 'com_content', 'com_articles') WHERE `link` LIKE '%com_content%';
UPDATE `#__menu` SET `link` = REPLACE(`link`, 'view=category&layout=blog', 'view=articles') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=category&layout=blog%';
UPDATE `#__menu` SET `link` = REPLACE(`link`, 'view=section&layout=blog', 'view=articles') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=section&layout=blog%';
UPDATE `#__menu` SET `link` = REPLACE(`link`, 'view=category', 'view=articles&layout=table') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=category%';
UPDATE `#__menu` SET `link` = REPLACE(`link`, 'view=section', 'view=articles&layout=table') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=section%';
UPDATE `#__menu` SET `link` = REPLACE(`link`, 'id=', 'category=') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=articles%';
UPDATE `#__menu` SET `link` = REPLACE(`link`, '&layout=blog', '') WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=articles%';
UPDATE `#__menu` SET `link` = REPLACE(`link`, 'view=frontpage', 'view=articles'), `params` = CONCAT_WS('\n', 'show_featured=1', `params`) WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=frontpage%';

# --------------------------------------------------------
# com_modules schema changes

DELETE FROM `#__modules` WHERE `module` = 'mod_footer';
DELETE FROM `#__modules` WHERE `module` = 'mod_wrapper';
DELETE FROM `#__modules` WHERE `module` = 'mod_stats';
DELETE FROM `#__modules` WHERE `module` = 'mod_whoisonline';
DELETE FROM `#__modules` WHERE `module` = 'mod_sections';


# --------------------------------------------------------
# change engine to InnoDB

ALTER TABLE `#__articles` ENGINE = INNODB;
ALTER TABLE `#__articles_featured` ENGINE = INNODB;
ALTER TABLE `#__categories` ENGINE = INNODB;
ALTER TABLE `#__components` ENGINE = INNODB;
ALTER TABLE `#__contacts` ENGINE = INNODB;
ALTER TABLE  `#__menu` ENGINE = INNODB;
ALTER TABLE  `#__menu_types` ENGINE = INNODB;
ALTER TABLE  `#__modules` ENGINE = INNODB;
ALTER TABLE  `#__modules_menu` ENGINE = INNODB;
ALTER TABLE  `#__plugins` ENGINE = INNODB;
ALTER TABLE  `#__users_sessions` ENGINE = INNODB;
ALTER TABLE  `#__weblinks` ENGINE = INNODB;