-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
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
  `extensions_extension_id` INT UNSIGNED,
  `created_by` INT UNSIGNED,
  `created_on` DATETIME,
  `modified_by` INT UNSIGNED,
  `modified_on` DATETIME,
  `locked_by` INT UNSIGNED,
  `locked_on` DATETIME,
  `access` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `params` TEXT,
  PRIMARY KEY (`pages_page_id`),
  CONSTRAINT `pages__pages_menu_id` FOREIGN KEY (`pages_menu_id`) REFERENCES `pages_menus` (`pages_menu_id`) ON DELETE CASCADE,
  CONSTRAINT `pages__link_id` FOREIGN KEY (`link_id`) REFERENCES `pages` (`pages_page_id`) ON DELETE CASCADE,
  INDEX `ix_published` (`published`),
  INDEX `ix_extensions_extension_id` (`extensions_extension_id`),
  INDEX `ix_home` (`home`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages_orderings`
--

CREATE TABLE `pages_orderings` (
  `pages_page_id` int(11) unsigned NOT NULL,
  `title` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  `custom` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  PRIMARY KEY (`pages_page_id`),
  KEY `ix_title` (`title`),
  KEY `ix_custom` (`custom`),
  CONSTRAINT `pages_orderings__pages_page_id` FOREIGN KEY (`pages_page_id`) REFERENCES `pages` (`pages_page_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages_closures`
--

CREATE TABLE `pages_closures` (
  `ancestor_id` INT UNSIGNED NOT NULL,
  `descendant_id` INT UNSIGNED NOT NULL,
  `level` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`ancestor_id`, `descendant_id`),
  CONSTRAINT `pages_closures__ancestor_id` FOREIGN KEY (`ancestor_id`) REFERENCES `pages` (`pages_page_id`) ON DELETE CASCADE,
  CONSTRAINT `pages_closures__descendant_id` FOREIGN KEY (`descendant_id`) REFERENCES `pages` (`pages_page_id`) ON DELETE CASCADE,
  INDEX `ix_level` (`level`),
  INDEX `ix_descendant_id` (`descendant_id`)
) ENGINE=InnoDB CHARSET = utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages_menus`
--

CREATE TABLE `pages_menus` (
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
-- Table structure for table `pages_modules`
--

CREATE TABLE `pages_modules_pages` (
  `pages_module_id` INT NOT NULL,
  `pages_page_id` INT NOT NULL,
  PRIMARY KEY (`pages_module_id`,`pages_page_id`),
  INDEX `ix_pages_page_id` (`pages_page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages_modules`
--

CREATE TABLE `pages_modules` (
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
  `extensions_extension_id` INT UNSIGNED,
  `application` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`published`,`access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
