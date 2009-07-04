

CREATE TABLE IF NOT EXISTS `#__beer_departments` (
  `beer_department_id` SERIAL,
  `title` varchar(250) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL default 0,
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default 0,
  `enabled` tinyint(1) NOT NULL default 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__beer_offices` (
  `beer_office_id` SERIAL,
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
  `created_by` int(11) NOT NULL default 0,
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default 0,
  `enabled` tinyint(1) NOT NULL default 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `#__beer_people` (
  `beer_person_id` SERIAL,
  `beer_department_id` bigint(20) UNSIGNED NOT NULL,
  `beer_office_id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(45) NOT NULL,
  `middlename` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `position` varchar(45) NOT NULL,
  `birthday` date NOT NULL default '0000-00-00',
  `gender` tinyint(3) NOT NULL,
  `mobile` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL default 0,
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` bigint(20) UNSIGNED NOT NULL default 0,
  `enabled` tinyint(1) NOT NULL default 1,
  KEY `department` (`beer_department_id`),
  KEY `office` (`beer_office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE OR REPLACE VIEW #__beer_viewpeople AS 
SELECT p.*, 
	CONCAT_WS(' ', p.`firstname`, p.`middlename`, p.`lastname`) AS name,
	d.title AS department,
	o.title AS office
FROM #__beer_people AS p
LEFT JOIN #__beer_departments AS d ON d.beer_department_id = p.beer_department_id
LEFT JOIN #__beer_offices AS o ON o.beer_office_id = p.beer_office_id;

CREATE OR REPLACE VIEW #__beer_viewdepartments AS 
SELECT d.*, 
	COUNT( DISTINCT p.beer_person_id ) AS people
FROM #__beer_departments AS d
LEFT JOIN #__beer_people AS p ON p.beer_department_id = d.beer_department_id AND p.enabled > 0
GROUP BY d.beer_department_id;

CREATE OR REPLACE VIEW #__beer_viewoffices AS 
SELECT o.*, 
	CONCAT_WS(' ', o.`address1`, o.`address2`, o.`city`) AS address, 
	COUNT( DISTINCT p.beer_person_id ) AS people
FROM #__beer_offices AS o
LEFT JOIN #__beer_people AS p ON p.beer_office_id = o.beer_office_id AND p.enabled > 0
GROUP BY o.beer_office_id;