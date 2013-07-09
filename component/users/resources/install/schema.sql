-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
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
  CONSTRAINT `users_user_role` FOREIGN KEY (`users_role_id`) REFERENCES `users_roles` (`users_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE `users_roles` (
  `users_role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`users_role_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `users_group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`users_group_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_groups_users`
--

CREATE TABLE `users_groups_users` (
  `users_group_id` int(11) unsigned NOT NULL,
  `users_user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`users_group_id`,`users_user_id`),
  CONSTRAINT `users_groups_users__users_user_id` FOREIGN KEY (`users_user_id`) REFERENCES `users` (`users_user_id`) ON DELETE CASCADE,
  CONSTRAINT `users_groups_users__users_group_id` FOREIGN KEY (`users_group_id`) REFERENCES `users_groups` (`users_group_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_sessions`
--
-- InnoDB tables can make a database slow down if not using incremental primary keys. We are storing the session hash as
-- primary key, which can result in big load times when the table grows. Hence why we use MyISAM for the session table.
--
-- More info please see : http://blog.johnjosephbachir.org/2006/10/22/everything-you-need-to-know-about-designing-mysql-innodb-primary-keys/
--

CREATE TABLE `users_sessions` (
  `time` varchar(14) DEFAULT '',
  `users_session_id` varchar(128) NOT NULL,
  `guest` tinyint(4) DEFAULT '1',
  `email` varchar(100) NOT NULL COMMENT '@Filter("email")',
  `application` varchar(50) NOT NULL,
  `data` longtext,
  PRIMARY KEY (`users_session_id`(64)),
  KEY `whosonline` (`guest`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_passwords`
--

CREATE TABLE `users_passwords` (
  `email` varchar(100) NOT NULL DEFAULT '',
  `expiration` date DEFAULT NULL,
  `hash` varchar(100) NOT NULL DEFAULT '',
  `reset` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`email`),
  CONSTRAINT `users_password__email` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;