-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 01, 2012 at 12:32 AM
-- Server version: 5.5.24
-- PHP Version: 5.3.12

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40101 SET @OLD_TIME_ZONE=@@TIME_ZONE, TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- --------------------------------------------------------

--
-- Table structure for table `#__activities_activities`
--

CREATE TABLE `#__activities_activities` (
    `activities_activity_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `application` VARCHAR(10) NOT NULL DEFAULT '',
    `type` VARCHAR(3) NOT NULL DEFAULT '',
    `package` VARCHAR(50) NOT NULL DEFAULT '',
    `name` VARCHAR(50) NOT NULL DEFAULT '',
    `action` VARCHAR(50) NOT NULL DEFAULT '',
    `row` BIGINT NOT NULL DEFAULT '0',
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `status` varchar(100) NOT NULL,
    `created_on` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` INT(11) NOT NULL DEFAULT '0',
    PRIMARY KEY(`activities_activity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__articles`
--

CREATE TABLE `#__articles` (
  `articles_article_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `categories_category_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) unsigned NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `unpublish_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `access` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`articles_article_id`),
  KEY `idx_access` (`access`),
  KEY `idx_state` (`published`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_catid` (`categories_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__articles_featured`
--

CREATE TABLE `#__articles_featured` (
  `articles_article_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`articles_article_id`)
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
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__components`
--

CREATE TABLE `#__extensions_components` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
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
-- Table structure for table `#__core_acl_aro`
--

CREATE TABLE `#__core_acl_aro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_value` varchar(240) NOT NULL DEFAULT '0',
  `value` varchar(240) NOT NULL DEFAULT '',
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `#__section_value_value_aro` (`section_value`(100),`value`(100)),
  KEY `#__gacl_hidden_aro` (`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_aro_groups`
--

CREATE TABLE `#__core_acl_aro_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `#__gacl_parent_id_aro_groups` (`parent_id`),
  KEY `#__gacl_lft_rgt_aro_groups` (`lft`,`rgt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_aro_map`
--

CREATE TABLE `#__core_acl_aro_map` (
  `acl_id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(230) NOT NULL DEFAULT '0',
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`acl_id`,`section_value`,`value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_aro_sections`
--

CREATE TABLE `#__core_acl_aro_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(230) NOT NULL DEFAULT '',
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(230) NOT NULL DEFAULT '',
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `#__gacl_value_aro_sections` (`value`),
  KEY `#__gacl_hidden_aro_sections` (`hidden`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__core_acl_groups_aro_map`
--

CREATE TABLE `#__core_acl_groups_aro_map` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(240) NOT NULL DEFAULT '',
  `aro_id` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `group_id_aro_id_groups_aro_map` (`group_id`,`section_value`,`aro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
    `extensions_component_id` INT UNSIGNED,
    `name` VARCHAR(64) NOT NULL,
    `unique_column` VARCHAR(64) NOT NULL,
    `enabled` BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (`languages_table_id`),
    CONSTRAINT `#__languages_tables__extensions_component_id` FOREIGN KEY (`extensions_component_id`) REFERENCES `#__extensions_components` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__pages_orderings`
--

CREATE TABLE `#__pages_orderings` (
  `pages_page_id` INT UNSIGNED NOT NULL,
  `title` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `custom` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`pages_page_id`),
  CONSTRAINT `#__pages_orderings__pages_page_id` FOREIGN KEY (`pages_page_id`) REFERENCES `#__pages` (`pages_page_id`) ON DELETE CASCADE,
  INDEX `ix_title` (`title`),
  INDEX `ix_custom` (`custom`)
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
  `pages_menu_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255),
  `description` VARCHAR(255),
  PRIMARY KEY (`pages_menu_id`)
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
  `showtitle` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `extensions_component_id` INT UNSIGNED,
  `application` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`published`,`access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__pages`
--

CREATE TABLE `#__pages` (
  `pages_page_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pages_menu_id` INT UNSIGNED NOT NULL,
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
-- Table structure for table `#__users`
--

CREATE TABLE `#__users` (
  `users_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `usertype` varchar(25) NOT NULL DEFAULT '',
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `send_email` tinyint(1) DEFAULT '0',
  `users_group_id` int(11) unsigned NOT NULL DEFAULT '1',
  `registered_on` datetime,
  `last_visited_on` datetime,
  `activation` varchar(100) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `uuid` char(36) NOT NULL,
  PRIMARY KEY (`users_user_id`),
  UNIQUE KEY `uuid` (`uuid`),
  UNIQUE KEY `email` (`email`),
  KEY `usertype` (`usertype`)
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
  `users_user_email` varchar(100) NOT NULL DEFAULT '',
  `expiration` date NOT NULL DEFAULT '0000-00-00',
  `hash` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`users_user_email`),
  CONSTRAINT `#__users_passwords__users_user_email` FOREIGN KEY (`users_user_email`) REFERENCES `#__users` (`email`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `#__weblinks`
--

CREATE TABLE `#__weblinks` (
  `weblinks_weblink_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `categories_category_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
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


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;