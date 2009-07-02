
--
-- Table structure for table `jos_directory_departments`
--

CREATE TABLE IF NOT EXISTS `jos_directory_departments` (
  `directory_department_id` int(11) NOT NULL auto_increment,
  `title` varchar(250) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL default '0',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default '0',
  `enabled` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`directory_department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `jos_directory_departments`
--


-- --------------------------------------------------------

--
-- Table structure for table `jos_directory_offices`
--

CREATE TABLE IF NOT EXISTS `jos_directory_offices` (
  `directory_office_id` int(11) NOT NULL auto_increment,
  `title` varchar(250) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(45) NOT NULL,
  `state` varchar(3) NOT NULL,
  `postcode` varchar(45) NOT NULL,
  `country` varchar(2) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL default '0',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default '0',
  `enabled` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`directory_office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `jos_directory_offices`
--


-- --------------------------------------------------------

--
-- Table structure for table `jos_directory_people`
--

CREATE TABLE IF NOT EXISTS `jos_directory_people` (
  `directory_person_id` int(11) NOT NULL auto_increment,
  `directory_department_id` int(11) NOT NULL,
  `directory_office_id` int(11) NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `middlename` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `position` varchar(45) NOT NULL,
  `birthday` date NOT NULL default '0000-00-00',
  `gender` tinyint(3) NOT NULL,
  `mobile` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL default '0',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default '0',
  `enabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`directory_person_id`),
  KEY `department` (`directory_department_id`),
  KEY `office` (`directory_office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `jos_directory_people`
--
