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

-- Remove administrator latest news module
-- http://nooku.assembla.com/spaces/nooku-server/tickets/217-remove-administrator-latest-news-module
DELETE FROM `#__modules` WHERE `id` = 4;

-- Remove mod_related_items
DELETE FROM `#__modules` WHERE `module` = 'mod_related_items';

# --------------------------------------------------------
# com_contacts schema changes

-- Rename contacts_details to contacts_contacts
RENAME TABLE  `#__contact_details` TO `#__contacts_contacts`;
UPDATE `#__categories` SET `section` = 'com_contacts_contacts' WHERE `section` = 'com_contact_details';

# --------------------------------------------------------
# com_users schema changes

-- Update timezone offsets in user params.
UPDATE `#__user` SET `params` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`params`, 'timezone=-12', 'timezone=Etc/GMT-12'), 'timezone=-11', 'timezone=Pacific/Midway'), 'timezone=-10', 'timezone=Pacific/Honolulu'), 'timezone=-9.5', 'timezone=Pacific/Marquesas'), 'timezone=-9', 'timezone=US/Alaska'), 'timezone=-8', 'timezone=US/Pacific'), 'timezone=-7', 'timezone=US/Mountain'), 'timezone=-6', 'timezone=US/Central'), 'timezone=-5', 'timezone=US/Eastern'), 'timezone=-4.5', 'timezone=America/Caracas'), 'timezone=-4', 'timezone=America/Barbados'), 'timezone=-3.5', 'timezone=Canada/Newfoundland'), 'timezone=-3', 'timezone=America/Buenos_Aires'), 'timezone=-2', 'timezone=Atlantic/South_Georgia'), 'timezone=-1', 'timezone=Atlantic/Azores'), 'timezone=0', 'timezone=Europe/London'), 'timezone=1', 'timezone=Europe/Amsterdam'), 'timezone=2', 'timezone=Europe/Istanbul'), 'timezone=3', 'timezone=Asia/Riyadh'), 'timezone=3.5', 'timezone=Asia/Tehran'), 'timezone=4', 'timezone=Asia/Muscat'), 'timezone=4.5', 'timezone=Asia/Kabul'), 'timezone=5', 'timezone=Asia/Karachi'), 'timezone=5.5', 'timezone=Asia/Calcutta'), 'timezone=5.75', 'timezone=Asia/Katmandu'), 'timezone=6', 'timezone=Asia/Dhaka'), 'timezone=6.5', 'timezone=Indian/Cocos'), 'timezone=7', 'timezone=Asia/Bangkok'), 'timezone=8', 'timezone=Australia/Perth'), 'timezone=8.75', 'timezone=Australia/West'), 'timezone=9', 'timezone=Asia/Tokyo'), 'timezone=9.5', 'timezone=Australia/Adelaide'), 'timezone=10', 'timezone=Australia/Brisbane'), 'timezone=10.5', 'timezone=Australia/Lord_Howe'), 'timezone=11', 'timezone=Pacific/Kosrae'), 'timezone=11.5', 'timezone=Pacific/Norfolk'), 'timezone=12', 'timezone=Pacific/Auckland'), 'timezone=12.75', 'timezone=Pacific/Chatham'), 'timezone=13', 'timezone=Pacific/Tongatapu'), 'timezone=14', 'timezone=Pacific/Kiritimati');

-- Remove unused columns from #__session
ALTER TABLE `#__session` DROP `username`;
ALTER TABLE `#__session` DROP `usertype`;
ALTER TABLE `#__session` DROP `gid`;
RENAME TABLE`#__session` TO  `#__users_sessions`;
ALTER TABLE  `#__users_sessions` CHANGE  `session_id`  `users_session_id` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '0';

# --------------------------------------------------------
# com_content schema changes

--  -- Upgrade menu items links
UPDATE `#__menu` SET `link` = REPLACE(`link`, 'com_content', 'com_articles') WHERE `link` LIKE '%com_content%';
UPDATE `#__menu` SET `link` = REPLACE(`link`, 'view=frontpage', 'view=articles'), `params` = CONCAT_WS('\n', 'show_featured=1', `params`) WHERE `link` LIKE '%com_articles%' AND `link` LIKE '%view=frontpage%';

-- Upgrade modules rows
UPDATE `#__modules` SET `module` = 'mod_articles', `params` = CONCAT_WS('\n', 'show_content=1', `params`) WHERE `module` = 'mod_newsflash';
UPDATE `#__modules` SET `module` = 'mod_articles' WHERE `module` = 'mod_latestnews';
UPDATE `#__modules` SET `params` = REPLACE(REPLACE(REPLACE(REPLACE(`params`, 'catid', 'category'), 'secid', 'section'), 'show_front', 'show_featured'), 'items', 'count') WHERE `module` = 'mod_articles';

-- Rename tables to follow conventions
RENAME TABLE `#__content` TO `#__articles_articles`;
RENAME TABLE `#__sections` TO `#__articles_sections`;
RENAME TABLE `#__content_frontpage` TO `#__articles_featured`;

-- Update schema to follow conventions
ALTER TABLE `#__articles_articles` CHANGE `id` `articles_article_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__articles_articles` CHANGE `sectionid` `articles_section_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `#__articles_featured` CHANGE `content_id` `articles_article_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `#__articles_sections` CHANGE `id` `articles_section_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;

UPDATE `#__categories` SET `section` = 'com_articles' WHERE `section` = 'com_content';

-- Remove loadmodule plugin
DELETE FROM `#__plugins` WHERE `element` = 'loadmodule' AND `folder` = 'content';

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
DROP TABLE `#__banner`;
DELETE FROM `#__components` WHERE `parent` = 1 OR `option` = 'com_banners';

-- Remove mod_feed
DELETE FROM `#__modules` WHERE `module` = 'mod_banners';

-- Remove menu links to banners component
DELETE FROM `#__menu` WHERE `componentid` = 1;