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