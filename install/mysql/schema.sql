SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET @OLD_TIME_ZONE=@@TIME_ZONE, TIME_ZONE='+00:00';
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

-- --------------------------------------------------------

--
-- Table structure for table `#__activities_activities`
--

CREATE TABLE `#__activities_activities` (
    `activities_activity_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(36) NOT NULL DEFAULT '' UNIQUE,
    `application` VARCHAR(10) NOT NULL DEFAULT '',
    `type` VARCHAR(3) NOT NULL DEFAULT '',
    `package` VARCHAR(50) NOT NULL DEFAULT '',
    `name` VARCHAR(50) NOT NULL DEFAULT '',
    `action` VARCHAR(50) NOT NULL DEFAULT '',
    `row` BIGINT NOT NULL DEFAULT '0',
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `status` varchar(100) NOT NULL,
    `created_on` datetime DEFAULT NULL,
    `created_by` INT(11) NOT NULL DEFAULT '0',
    `ip` varchar(255) DEFAULT NULL,
	PRIMARY KEY(`activities_activity_id`),
	KEY `idx-ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__articles`
--

CREATE TABLE `#__articles` (
  `articles_article_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `categories_category_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created_by` int(11) unsigned DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(11) unsigned DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `locked_by` int(11) unsigned DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `publish_on` datetime DEFAULT NULL,
  `unpublish_on` datetime DEFAULT NULL,
  `params` text,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `description` text,
  `access` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`articles_article_id`),
  KEY `idx_access` (`access`),
  KEY `idx_state` (`published`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_catid` (`categories_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__attachments`
--

CREATE TABLE `#__attachments` (
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
  PRIMARY KEY (`attachments_attachment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__attachments_relations`
--

CREATE TABLE `#__attachments_relations` (
  `attachments_attachment_id` int(10) unsigned NOT NULL,
  `table` varchar(64) NOT NULL,
  `row` int(10) unsigned NOT NULL,
  KEY `attachments_attachment_id` (`attachments_attachment_id`),
  CONSTRAINT `#__attachments_relations_ibfk_1` FOREIGN KEY (`attachments_attachment_id`) REFERENCES `#__attachments` (`attachments_attachment_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__categories`
--

CREATE TABLE `#__categories` (
  `categories_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `table` varchar(50) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` INT UNSIGNED,
  `created_on` DATETIME,
  `modified_by` INT UNSIGNED,
  `modified_on` DATETIME,
  `locked_by` INT UNSIGNED,
  `locked_on` DATETIME,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  PRIMARY KEY (`categories_category_id`),
  KEY `cat_idx` (`table`,`published`,`access`),
  KEY `idx_access` (`access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__comments`
--

CREATE TABLE `#__comments` (
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

-- --------------------------------------------------------

--
-- Table structure for table `#__contacts`
--

CREATE TABLE `#__contacts` (
  `contacts_contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `address` text,
  `suburb` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postcode` varchar(100) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `misc` mediumtext,
  `image` varchar(255) DEFAULT NULL,
  `email_to` varchar(255) DEFAULT NULL,
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_by` int(11) unsigned,
  `created_on` datetime,
  `modified_by` int(11) unsigned,
  `modified_on` datetime,
  `locked_by` int(11) unsigned,
  `locked_on` datetime,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `categories_category_id` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(255) NOT NULL DEFAULT '',
  `webpage` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`contacts_contact_id`),
  KEY `category` (`categories_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__extensions_components`
--

CREATE TABLE `#__extensions_components` (
  `extensions_component_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`extensions_component_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__files_containers`
--

CREATE TABLE `#__files_containers` (
  `files_container_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `parameters` text NOT NULL,
  PRIMARY KEY (`files_container_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__files_thumbnails`
--

CREATE TABLE `#__files_thumbnails` (
  `files_thumbnail_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `files_container_id` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `thumbnail` text NOT NULL,
  PRIMARY KEY (`files_thumbnail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__languages`
--

CREATE TABLE `#__languages` (
    `languages_language_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `application` VARCHAR(50) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `native_name` VARCHAR(150) NOT NULL,
    `iso_code` VARCHAR(8) NOT NULL,
    `slug` VARCHAR(50) NOT NULL,
    `enabled` BOOLEAN NOT NULL DEFAULT 0,
    `primary` BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (`languages_language_id`)
) ENGINE = InnoDB CHARSET = utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__languages_translations`
--

CREATE TABLE `#__languages_translations` (
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

-- --------------------------------------------------------

--
-- Table structure for table `#__languages_tables`
--

CREATE TABLE `#__languages_tables` (
    `languages_table_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `extensions_component_id` INT(11) UNSIGNED,
    `name` VARCHAR(64) NOT NULL,
    `unique_column` VARCHAR(64) NOT NULL,
    `enabled` BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (`languages_table_id`),
    CONSTRAINT `#__languages_tables__extensions_component_id` FOREIGN KEY (`extensions_component_id`) REFERENCES `#__extensions_components` (`extensions_component_id`) ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__pages`
--

CREATE TABLE `#__pages` (
  `pages_page_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pages_menu_id` INT UNSIGNED NOT NULL,
  `users_group_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255),
  `link_url` TEXT,
  `link_id` INT UNSIGNED,
  `type` VARCHAR(50),
  `published` BOOLEAN NOT NULL DEFAULT 0,
  `hidden` BOOLEAN NOT NULL DEFAULT 0,
  `home` BOOLEAN NOT NULL DEFAULT 0,
  `extensions_component_id` INT UNSIGNED,
  `created_by` INT UNSIGNED,
  `created_on` DATETIME,
  `modified_by` INT UNSIGNED,
  `modified_on` DATETIME,
  `locked_by` INT UNSIGNED,
  `locked_on` DATETIME,
  `access` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `params` TEXT,
  PRIMARY KEY (`pages_page_id`),
  CONSTRAINT `#__pages__pages_menu_id` FOREIGN KEY (`pages_menu_id`) REFERENCES `#__pages_menus` (`pages_menu_id`) ON DELETE CASCADE,
  CONSTRAINT `#__pages__link_id` FOREIGN KEY (`link_id`) REFERENCES `#__pages` (`pages_page_id`) ON DELETE CASCADE,
  INDEX `ix_published` (`published`),
  INDEX `ix_extensions_component_id` (`extensions_component_id`),
  INDEX `ix_home` (`home`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `#__pages_orderings`
--

CREATE TABLE `#__pages_orderings` (
  `pages_page_id` int(11) unsigned NOT NULL,
  `title` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  `custom` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  PRIMARY KEY (`pages_page_id`),
  KEY `ix_title` (`title`),
  KEY `ix_custom` (`custom`),
  CONSTRAINT `#__pages_orderings__pages_page_id` FOREIGN KEY (`pages_page_id`) REFERENCES `#__pages` (`pages_page_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__pages_closures`
--

CREATE TABLE `#__pages_closures` (
  `ancestor_id` INT UNSIGNED NOT NULL,
  `descendant_id` INT UNSIGNED NOT NULL,
  `level` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`ancestor_id`, `descendant_id`),
  CONSTRAINT `#__pages_closures__ancestor_id` FOREIGN KEY (`ancestor_id`) REFERENCES `#__pages` (`pages_page_id`) ON DELETE CASCADE,
  CONSTRAINT `#__pages_closures__descendant_id` FOREIGN KEY (`descendant_id`) REFERENCES `#__pages` (`pages_page_id`) ON DELETE CASCADE,
  INDEX `ix_level` (`level`),
  INDEX `ix_descendant_id` (`descendant_id`)
) ENGINE=InnoDB CHARSET = utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__pages_menus`
--

CREATE TABLE `#__pages_menus` (
  `pages_menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `application` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `locked_by` int(10) unsigned DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  PRIMARY KEY (`pages_menu_id`),
  KEY `ix_application` (`application`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__pages_modules`
--

CREATE TABLE `#__pages_modules_pages` (
  `modules_module_id` INT NOT NULL,
  `pages_page_id` INT NOT NULL,
  PRIMARY KEY (`modules_module_id`,`pages_page_id`),
  INDEX `ix_pages_page_id` (`pages_page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__pages_modules`
--

CREATE TABLE `#__pages_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `position` varchar(50) DEFAULT NULL,
  `created_by` INT UNSIGNED,
  `created_on` DATETIME,
  `modified_by` INT UNSIGNED,
  `modified_on` DATETIME,
  `locked_by` INT UNSIGNED,
  `locked_on` DATETIME,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `extensions_component_id` INT UNSIGNED,
  `application` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`published`,`access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__terms`
--

CREATE TABLE IF NOT EXISTS `#__terms` (
	`terms_term_id` bigint(20) unsigned NOT NULL auto_increment,
	`title` VARCHAR( 255 ) NOT NULL,
	`slug` VARCHAR( 255 ) NOT NULL,
	`params` text NOT NULL,
	`created_by` int(10) unsigned DEFAULT NULL,
    `created_on` datetime DEFAULT NULL,
    `modified_by` int(10) unsigned DEFAULT NULL,
    `modified_on` datetime DEFAULT NULL,
    `locked_by` int(10) unsigned DEFAULT NULL,
    `locked_on` datetime DEFAULT NULL,
	PRIMARY KEY ( `terms_term_id` ) ,
	UNIQUE KEY ( `slug` ),
	UNIQUE KEY ( `title` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__terms_relations`
--

CREATE TABLE IF NOT EXISTS `#__terms_relations` (
	`terms_term_id` BIGINT(20) UNSIGNED NOT NULL,
  	`row` BIGINT(20) UNSIGNED NOT NULL,
  	`table` VARCHAR( 255 ) NOT NULL,
  	PRIMARY KEY  (`terms_term_id`,`row`,`table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__users`
--

CREATE TABLE `#__users` (
  `users_user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `send_email` tinyint(1) DEFAULT '0',
  `users_role_id` int(11) unsigned NOT NULL DEFAULT '18',
  `last_visited_on` datetime DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `locked_by` int(10) unsigned DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `activation` varchar(100) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `uuid` char(36) NOT NULL,
  PRIMARY KEY (`users_user_id`),
  UNIQUE KEY `uuid` (`uuid`),
  UNIQUE KEY `email` (`email`),
  KEY `users_role_id` (`users_role_id`),
  CONSTRAINT `users_user_role` FOREIGN KEY (`users_role_id`) REFERENCES `#__users_roles` (`users_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__users_roles`
--

CREATE TABLE `#__users_roles` (
  `users_role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`users_role_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__users_groups`
--

CREATE TABLE `#__users_groups` (
  `users_group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`users_group_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__users_groups_users`
--

CREATE TABLE `#__users_groups_users` (
  `users_group_id` int(11) unsigned NOT NULL,
  `users_user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`users_group_id`,`users_user_id`),
  CONSTRAINT `#__users_groups_users__users_user_id` FOREIGN KEY (`users_user_id`) REFERENCES `#__users` (`users_user_id`) ON DELETE CASCADE,
  CONSTRAINT `#__users_groups_users__users_group_id` FOREIGN KEY (`users_group_id`) REFERENCES `#__users_groups` (`users_group_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__users_sessions`
--

CREATE TABLE `#__users_sessions` (
  `time` varchar(14) DEFAULT '',
  `users_session_id` varchar(128) NOT NULL,
  `guest` tinyint(4) DEFAULT '1',
  `email` varchar(100) NOT NULL COMMENT '@Filter("email")',
  `application` varchar(50) NOT NULL,
  `data` longtext,
  PRIMARY KEY (`users_session_id`(64)),
  KEY `whosonline` (`guest`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__users_passwords`
--

CREATE TABLE `#__users_passwords` (
  `email` varchar(100) NOT NULL DEFAULT '',
  `expiration` date DEFAULT NULL,
  `hash` varchar(100) NOT NULL DEFAULT '',
  `reset` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`email`),
  CONSTRAINT `#__users_password__email` FOREIGN KEY (`email`) REFERENCES `#__users` (`email`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__versions_revisions`
--

CREATE TABLE `#__versions_revisions` (
  `table` varchar(64) NOT NULL,
  `row` bigint(20) unsigned NOT NULL,
  `revision` bigint(20) unsigned NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `data` longtext NOT NULL COMMENT '@Filter("json")',
  `status` varchar(100) NOT NULL,
  PRIMARY KEY  (`table`,`row`,`revision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__weblinks`
--

CREATE TABLE `#__weblinks` (
  `weblinks_weblink_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `categories_category_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(11) unsigned,
  `created_on` datetime,
  `modified_by` int(11) unsigned,
  `modified_on` datetime,
  `locked_by` int(11) unsigned,
  `locked_on` datetime,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  PRIMARY KEY (`weblinks_weblink_id`),
  KEY `category` (`categories_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET SQL_MODE=@OLD_SQL_MODE;
SET TIME_ZONE=@OLD_TIME_ZONE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;