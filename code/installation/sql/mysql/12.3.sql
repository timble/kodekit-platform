# Remove legacy plugin
# http://nooku.assembla.com/spaces/nooku-server/tickets/191-remove-joomla-legacy-support
DELETE FROM `#__plugins` WHERE `id` = 29;

# Remove Joomla user plugin
DELETE FROM `#__plugins` WHERE `id` = 5;

# Remove Joomla authentication plugin
DELETE FROM `#__plugins` WHERE `id` = 1;

# Remove editor and editor-xtd plugins
DELETE FROM `#__plugins` WHERE `folder` = 'editors' OR `folder` = 'editors-xtd';

# Remove administrator latest news module
# http://nooku.assembla.com/spaces/nooku-server/tickets/217-remove-administrator-latest-news-module
DELETE FROM `#__modules` WHERE `id` = 4;

# Rename contacts_details to contacts_contacts
RENAME TABLE  `#__contact_details` TO `#__contacts_contacts`;
UPDATE `#__categories` SET `section` = 'com_contacts_contacts' WHERE `section` = 'com_contact_details';

# Update timezone offsets in user params.
UPDATE `#__user` SET `params` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`params`, 'timezone=-12', 'timezone=Etc/GMT-12'), 'timezone=-11', 'timezone=Pacific/Midway'), 'timezone=-10', 'timezone=Pacific/Honolulu'), 'timezone=-9.5', 'timezone=Pacific/Marquesas'), 'timezone=-9', 'timezone=US/Alaska'), 'timezone=-8', 'timezone=US/Pacific'), 'timezone=-7', 'timezone=US/Mountain'), 'timezone=-6', 'timezone=US/Central'), 'timezone=-5', 'timezone=US/Eastern'), 'timezone=-4.5', 'timezone=America/Caracas'), 'timezone=-4', 'timezone=America/Barbados'), 'timezone=-3.5', 'timezone=Canada/Newfoundland'), 'timezone=-3', 'timezone=America/Buenos_Aires'), 'timezone=-2', 'timezone=Atlantic/South_Georgia'), 'timezone=-1', 'timezone=Atlantic/Azores'), 'timezone=0', 'timezone=Europe/London'), 'timezone=1', 'timezone=Europe/Amsterdam'), 'timezone=2', 'timezone=Europe/Istanbul'), 'timezone=3', 'timezone=Asia/Riyadh'), 'timezone=3.5', 'timezone=Asia/Tehran'), 'timezone=4', 'timezone=Asia/Muscat'), 'timezone=4.5', 'timezone=Asia/Kabul'), 'timezone=5', 'timezone=Asia/Karachi'), 'timezone=5.5', 'timezone=Asia/Calcutta'), 'timezone=5.75', 'timezone=Asia/Katmandu'), 'timezone=6', 'timezone=Asia/Dhaka'), 'timezone=6.5', 'timezone=Indian/Cocos'), 'timezone=7', 'timezone=Asia/Bangkok'), 'timezone=8', 'timezone=Australia/Perth'), 'timezone=8.75', 'timezone=Australia/West'), 'timezone=9', 'timezone=Asia/Tokyo'), 'timezone=9.5', 'timezone=Australia/Adelaide'), 'timezone=10', 'timezone=Australia/Brisbane'), 'timezone=10.5', 'timezone=Australia/Lord_Howe'), 'timezone=11', 'timezone=Pacific/Kosrae'), 'timezone=11.5', 'timezone=Pacific/Norfolk'), 'timezone=12', 'timezone=Pacific/Auckland'), 'timezone=12.75', 'timezone=Pacific/Chatham'), 'timezone=13', 'timezone=Pacific/Tongatapu'), 'timezone=14', 'timezone=Pacific/Kiritimati');

# Remove unused columns from #__session
ALTER TABLE `#__session` DROP `username`;
ALTER TABLE `#__session` DROP `usertype`;
ALTER TABLE `#__session` DROP `gid`;
RENAME TABLE`#__session` TO  `#__users_sessions`;
ALTER TABLE  `#__users_sessions` CHANGE  `session_id`  `users_session_id` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '0';

CREATE TABLE IF NOT EXISTS `#__users_blackhosts` (
  `users_blacklistedhost_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`users_blacklistedhost_id`),
  UNIQUE KEY `idx-name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__users_spammers` (
  `users_spammer_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET latin1 NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `username` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`users_spammer_id`),
  KEY `idx-ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__users_whiteips` (
  `ip` varchar(255) NOT NULL DEFAULT '',
  `note` text,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=articles', `params` = 'articles_per_page=5\nshow_featured=1\nsort_by=newest\nshow_feed_link=1\nshow_create_date=1\nshow_modify_date=1\nshow_readmore=1\npage_title=Welcome to the Frontpage\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n' WHERE `id` = 1;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=article&id=5', `params` = 'show_create_date=1\nshow_modify_date=1\nshow_readmore=1\npage_title=\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n' WHERE `id` = 2;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=section&id=3', `params` = 'show_description=0\nshow_description_image=0\nshow_empty_categories=0\nshow_cat_num_articles=1\nshow_category_description=1\nsort_by=newest\nshow_feed_link=1\nshow_create_date=1\nshow_modify_date=1\nshow_readmore=1\npage_title=\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n' WHERE `id` = 41;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=article&id=24', `params` = 'pageclass_sfx=\nmenu_image=-1\nsecure=0\nshow_noauth=0\nlink_titles=0\nshow_intro=1\nshow_section=0\nlink_section=0\nshow_category=0\nlink_category=0\nshow_author=1\nshow_create_date=1\nshow_modify_date=1\nshow_item_navigation=0\nshow_readmore=1\nshow_vote=0\nshow_icons=1\nshow_pdf_icon=1\nshow_print_icon=1\nshow_email_icon=1\nshow_hits=1\n\n' WHERE `id` = 38;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=article&id=19', `params` = 'show_create_date=1\nshow_modify_date=1\nshow_readmore=1\npage_title=\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n' WHERE `id` = 27;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=article&id=26', `params` = 'show_create_date=1\nshow_modify_date=1\nshow_readmore=1\npage_title=\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n' WHERE `id` = 40;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=section&id=4', `params` = 'show_description=0\nshow_description_image=0\nshow_empty_categories=0\nshow_cat_num_articles=1\nshow_category_description=1\nsort_by=newest\nshow_feed_link=1\nshow_create_date=1\nshow_modify_date=1\nshow_readmore=1\npage_title=\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n' WHERE `id` = 37;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=article&id=43', `params` = 'show_create_date=1\nshow_modify_date=1\nshow_readmore=1\npage_title=\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n' WHERE `id` = 43;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=section&layout=blog&id=3', `params` = 'show_description=0\nshow_description_image=0\narticles_per_page=5\nsort_by=newest\nshow_feed_link=1\nshow_create_date=1\nshow_modify_date=1\nshow_readmore=1\npage_title=Example of Section Blog layout (FAQ section)\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n' WHERE `id` = 44;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=section&id=3', `params` = 'show_description=0\nshow_description_image=0\nshow_empty_categories=0\nshow_cat_num_articles=1\nshow_category_description=1\nsort_by=newest\nshow_feed_link=1\nshow_create_date=1\nshow_modify_date=1\nshow_readmore=1\npage_title=Example of Table Blog layout (FAQ section)\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n' WHERE `id` = 45;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=category&layout=blog&id=31', `params` = 'show_description=0\nshow_description_image=0\narticles_per_page=5\nsort_by=newest\nshow_feed_link=1\nshow_create_date=1\nshow_modify_date=1\nshow_readmore=1\npage_title=Example of Category Blog layout (FAQs/General category)\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n' WHERE `id` = 46;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=category&id=32', `params` = 'show_headings=1\nshow_date=0\ndate_format=\narticles_per_page=2\nsort_by=newest\nshow_feed_link=1\nshow_create_date=\nshow_modify_date=\nshow_readmore=\npage_title=Example of Category Table layout (FAQs/Languages category)\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n' WHERE `id` = 47;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=category&layout=blog&id=1', `params` = 'show_description=0\nshow_description_image=0\narticles_per_page=5\nsort_by=newest\nshow_feed_link=1\nshow_create_date=1\nshow_modify_date=1\nshow_readmore=1\npage_title=The News\nshow_page_title=1\npageclass_sfx=\nsecure=0\n\n' WHERE `id` = 50;
UPDATE `#__menu` SET `link` = 'index.php?option=com_articles&view=article&layout=form' WHERE `id` = 51;
