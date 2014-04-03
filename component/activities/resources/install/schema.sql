
-- --------------------------------------------------------
--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activities_activity_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `application` VARCHAR(10) NOT NULL DEFAULT '',
  `package` VARCHAR(50) NOT NULL DEFAULT '',
  `name` VARCHAR(50) NOT NULL DEFAULT '',
  `action` VARCHAR(50) NOT NULL DEFAULT '',
  `row` varchar(2048) NOT NULL DEFAULT '',
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `status` varchar(100) NOT NULL,
  `created_on` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` INT(11) NOT NULL DEFAULT '0',
  `ip` varchar(45) NOT NULL DEFAULT '',
  `metadata` text NOT NULL,
  `uuid` char(36) NOT NULL,
  PRIMARY KEY(`activities_activity_id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `package` (`package`),
  KEY `name` (`name`),
  KEY `row` (`row`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;