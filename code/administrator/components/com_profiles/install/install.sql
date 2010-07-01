-- $Id$

CREATE TABLE IF NOT EXISTS `jos_profiles_departments` (
  `profiles_department_id` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(250) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL COMMENT '@Filter("html, tidy")',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL default 0,
  `modified_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default 0,
  `locked_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL default 0,
  `enabled` tinyint(1) SIGNED  NOT NULL default 1,
  PRIMARY KEY  (`profiles_department_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_profiles_offices` (
  `profiles_office_id` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(250) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL COMMENT '@Filter("html, tidy")',
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(45) NOT NULL,
  `state` varchar(3) NOT NULL,
  `postcode` varchar(45) NOT NULL,
  `country` varchar(2) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `coordinates` varchar(250) NOT NULL,
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL default 0,
  `modified_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default 0,
  `locked_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL default 0,
  `enabled` tinyint(1) SIGNED NOT NULL default 1,
  PRIMARY KEY  (`profiles_office_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `jos_profiles_people` (
  `profiles_person_id` bigint(20) unsigned NOT NULL auto_increment,
  `profiles_department_id` bigint(20) UNSIGNED NOT NULL,
  `profiles_office_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NULL,
  `firstname` varchar(45) NOT NULL,
  `middlename` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `position` varchar(45) NOT NULL,
  `birthday` date NOT NULL default '0000-00-00',
  `gender` tinyint(3) NOT NULL,
  `mobile` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL COMMENT '@Filter("email")',
  `bio` text NOT NULL COMMENT '@Filter("html, tidy")',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` bigint(20) UNSIGNED NOT NULL default 0,
  `modified_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` bigint(20) UNSIGNED NOT NULL default 0,
  `locked_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL default 0,
  `enabled` tinyint(1) SIGNED NOT NULL default 1,
  `hits` int(11) SIGNED NOT NULL default 0,
  PRIMARY KEY  (`profiles_person_id`),
  KEY `department` (`profiles_department_id`),
  KEY `office` (`profiles_office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE OR REPLACE VIEW jos_profiles_view_people AS 
SELECT p.*, 
	CONCAT_WS(', ', p.`lastname`, p.`firstname`) AS name,
	IF(d.enabled < 1, CONCAT('[', d.title, ']'), d.title) AS department,
	d.enabled AS department_enabled, 
	d.slug AS department_slug,
	IF(o.enabled < 1, CONCAT('[', o.title, ']'), o.title) AS office,
	o.enabled AS office_enabled,
	CONCAT_WS('\n', o.address1, o.address2, CONCAT_WS(' ', o.city, o.state, o.postcode), o.country) AS address,
	o.phone, o.address1, o.address2, o.city, o.state, o.postcode, o.country, o.fax, o.coordinates,
	o.slug AS office_slug,
	u.name AS user_name,
	u.username AS user_username,
	u.email AS user_email,
	LEFT(p.lastname, 1) AS letter_name
FROM jos_profiles_people AS p
LEFT JOIN jos_profiles_departments AS d ON d.profiles_department_id = p.profiles_department_id
LEFT JOIN jos_profiles_offices AS o ON o.profiles_office_id = p.profiles_office_id
LEFT JOIN jos_users AS u ON u.id = p.user_id;


CREATE OR REPLACE VIEW jos_profiles_view_departments AS 
SELECT d.*, 
	COUNT( DISTINCT p.profiles_person_id ) AS people
FROM jos_profiles_departments AS d
LEFT JOIN jos_profiles_people AS p ON p.profiles_department_id = d.profiles_department_id AND p.enabled > 0
GROUP BY d.profiles_department_id;


CREATE OR REPLACE VIEW jos_profiles_view_offices AS 
SELECT o.*, 
	COUNT( DISTINCT p.profiles_person_id ) AS people,
	CONCAT_WS('\n', address1, address2, CONCAT_WS(' ', city, state, postcode), country) AS address
FROM jos_profiles_offices AS o
LEFT JOIN jos_profiles_people AS p ON p.profiles_office_id = o.profiles_office_id AND p.enabled > 0
GROUP BY o.profiles_office_id;


CREATE OR REPLACE VIEW jos_profiles_users AS
SELECT u.*
FROM jos_users AS u;

-- --------------------------------------------------------

--
-- Dumping data for table `jos_profiles_departments`
--

INSERT IGNORE INTO `jos_profiles_departments` (`profiles_department_id`, `title`, `slug`, `description`, `created_on`, `created_by`, `modified_on`, `modified_by`, `enabled`) VALUES
(1, 'Marketing', 'marketing', 'The world-class marketing team at Showdown is focused on leading-edge hardware and software that define the solutions that customers want, prompting the competition to emulate us. As the only company that designs the hardware, the software, and the operating system, we stand alone in our ability to innovate beyond the status quo. Part of what drives this innovation is our challenging and creative environment and the fierce dedication and talent of our team. In marketing, you have the unique opportunity to work on revolutionary products from concept to launch with the best creative minds in the industry.', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(2, 'Sales', 'sales', 'Showdown is committed to delivering the finest and most innovative computing solutions to students, educators, consumers, businesses, and creative professionals around the world. On the Sales team, our primary focus is to drive revenue for hardware, software, and professional services. One of the benefits of selling Showdown products is that they are completely integrated platforms. We focus on selling the value inherent in the complete product, rather than just individual boxes, and giving our customers a solution that address their needs. Our high-performance sales teams constantly strive to increase customer satisfaction and grow our market share.', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(3, 'Finance', 'finance', 'The Finance department is an integral part of Showdown\\''s success, supporting the growth and change of all functional areas of the company with flexibility and integrity. Having a team of talented thinkers who can balance a detail-oriented and quantifiable function within a dynamic, forward-thinking organization enables Showdown to create products that defy the status quo. The Finance department at Showdown offers opportunities for career development and growth as varied and engaging as the products we build.', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(4, 'Applications', 'applications', 'The Applications division develops simply awesome consumer and professional applications for digital media and personal productivity. Our people are both super-technical and abundantly creative. They\\''re engineers and marketers but they\\''re also musicians, filmmakers, photographers, composers and digital artists.', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(5, 'Information Systems & Technology', 'information-systems--technology', 'Information Systems & Technology (IS&T) drives Showdown\\''s corporate systems, retail systems and other key infrastructure. IS&T offers you tremendous opportunities to work on a wide variety of projects, from developing new tools, to implement standards-based applications, to managing Showdown\\''s email and telephone systems. The team uses industry-standard technologies such as SAP, PeopleSoft, Oracle and AIX, but also creates customized programs when off-the-shelf products don\\''t meet Showdown\\''s needs. If a global IT/MIS shop like no other is the challenge you\\''re looking for, we\\''re looking for you.', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1);


-- --------------------------------------------------------

--
-- Dumping data for table `jos_profiles_people`
--

INSERT IGNORE INTO `jos_profiles_people` (`profiles_person_id`, `profiles_department_id`, `profiles_office_id`, `firstname`, `middlename`, `lastname`, `slug`, `position`, `birthday`, `gender`, `mobile`, `email`, `bio`, `created_on`, `created_by`, `modified_on`, `modified_by`, `enabled`) VALUES
(2, 1, 1, 'Eberhardt', '', 'Terkki', '2-eberhardt-terkki', 'Employee', '1964-06-02', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(3, 1, 2, 'Bamford', '', 'Parto', '3-bamford-parto', 'Employee', '1959-11-30', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:13', 62, '1970-01-01 01:00:00', 0, 1),
(4, 1, 3, 'Chirstian', '', 'Koblick', '4-chirstian-koblick', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:18', 62, '1970-01-01 01:00:00', 0, 1),
(5, 1, 4, 'Kyoichi', '', 'Maliniak', '5-kyoichi-maliniak', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:22', 62, '1970-01-01 01:00:00', 0, 1),
(6, 1, 5, 'Tzvetan', '', 'Zielinski', '6-tzvetan-zielinski', 'Employee', '1953-04-20', 1, '147258369', 'info@show.down', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(7, 1, 6, 'Saniya', '', 'Kalloufi', 'è-saniya-kalloufi', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(8, 1, 7, 'Sumant', '', 'Peac', '8-sumant-peac', 'Employee', '1954-05-01', 2, '147258369', 'info@down.show', '', '2009-07-05 23:24:51', 62, '1970-01-01 01:00:00', 0, 1),
(9, 1, 8, 'Duangkaew', '', 'Piveteau', '9-duangkaew-piveteau', 'Employee', '1959-12-05', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:26', 62, '1970-01-01 01:00:00', 0, 1),
(10, 1, 9, 'Mary', '', 'Sluis', '10-mary-sluis', 'Employee', '1986-01-08', 2, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(11, 1, 10, 'Patricio', '', 'Bridgland', '11-patricio-bridgland', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(12, 2, 1, 'Bezalel', '', 'Simmel', '12-bezalel-simmel', 'Employee', '1964-06-02', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(13, 2, 2, 'Berni', '', 'Genin', '13-berni-genin', 'Employee', '1959-11-30', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:31', 62, '1970-01-01 01:00:00', 0, 1),
(14, 2, 3, 'Guoxiang', '', 'Nooteboom', '14-guoxiang-nooteboom', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:36', 62, '1970-01-01 01:00:00', 0, 1),
(15, 2, 4, 'Cristinel', '', 'Bouloucos', '15-cristinel-bouloucos', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:43', 62, '1970-01-01 01:00:00', 0, 1),
(16, 2, 5, 'Kazuhide', '', 'Peha', '16-kazuhide-peha', 'Employee', '1953-04-20', 1, '147258369', 'info@show.down', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(17, 2, 6, 'Lillian', '', 'Haddadi', '17-lillian-haddadi', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(18, 2, 7, 'Mayuko', '', 'Warwick', '18-mayuko-warwick', 'Employee', '1954-05-01', 2, '147258369', 'info@down.show', '', '2009-07-05 23:24:59', 62, '1970-01-01 01:00:00', 0, 1),
(19, 2, 8, 'Ramzi', '', 'Erde', '19-ramzi-erde', 'Employee', '1959-12-05', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:49', 62, '1970-01-01 01:00:00', 0, 1),
(20, 2, 9, 'Shahaf', '', 'Famili', '20-shahaf-famili', 'Employee', '1986-01-08', 2, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(21, 2, 10, 'Bojan', '', 'Montemayor', '21-bojan-montemayor', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(22, 3, 1, 'Suzette', '', 'Pettey', '22-suzette-pettey', 'Employee', '1964-06-02', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(23, 3, 2, 'Prasadram', '', 'Heyers', '23-prasadram-heyers', 'Employee', '1959-11-30', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:54', 62, '1970-01-01 01:00:00', 0, 1),
(24, 3, 3, 'Yongqiao', '', 'Berztiss', '24-yongqiao-berztiss', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:33:06', 62, '1970-01-01 01:00:00', 0, 1),
(25, 3, 4, 'Divier', '', 'Reistad', '25-divier-reistad', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:59', 62, '1970-01-01 01:00:00', 0, 1),
(26, 3, 5, 'Domenick', '', 'Tempesti', '26-domenick-tempesti', 'Employee', '1953-04-20', 1, '147258369', 'info@show.down', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(27, 3, 6, 'Otmar', '', 'Herbst', '27-otmar-herbst', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(28, 3, 7, 'Elvis', '', 'Demeyer', '28-elvis-demeyer', 'Employee', '1954-05-01', 2, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(29, 3, 8, 'Karsten', '', 'Joslin', '29-karsten-joslin', 'Employee', '1959-12-05', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:07', 62, '1970-01-01 01:00:00', 0, 1),
(30, 3, 9, 'Jeong', '', 'Reistad', '30-jeong-reistad', 'Employee', '1986-01-08', 2, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(31, 3, 10, 'Arif', '', 'Merlo', '31-arif-merlo', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(32, 4, 1, 'Bader', '', 'Swan', '32-bader-swan', 'Employee', '1964-06-02', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(33, 4, 2, 'Berni', '', 'Genin', '33-berni-genin', 'Employee', '1959-11-30', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(34, 4, 3, 'Guoxiang', '', 'Nooteboom', '34-guoxiang-nooteboom', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(35, 4, 4, 'Cristinel', '', 'Bouloucos', '35-cristinel-bouloucos', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(36, 4, 5, 'Kazuhide', '', 'Peha', '36-kazuhide-peha', 'Employee', '1953-04-20', 1, '147258369', 'info@show.down', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(37, 4, 6, 'Lillian', '', 'Haddadi', '37-lillian-haddadi', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(38, 4, 7, 'Mayuko', '', 'Warwick', '38-mayuko-warwick', 'Employee', '1954-05-01', 2, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(39, 4, 8, 'Ramzi', '', 'Erde', '39-ramzi-erde', 'Employee', '1959-12-05', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(40, 4, 9, 'Shahaf', '', 'Famili', '40-shahaf-famili', 'Employee', '1986-01-08', 2, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(41, 4, 10, 'Bojan', '', 'Montemayor', '41-bojan-montemayor', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(42, 5, 1, 'Suzette', '', 'Pettey', '42-suzette-pettey', 'Employee', '1964-06-02', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(43, 5, 2, 'Prasadram', '', 'Heyers', '43-prasadram-heyers', 'Employee', '1959-11-30', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(44, 5, 3, 'Yongqiao', '', 'Berztiss', '44-yongqiao-berztiss', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(45, 5, 4, 'Divier', '', 'Reistad', '45-divier-reistad', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(46, 5, 5, 'Domenick', '', 'Tempesti', '46-domenick-tempesti', 'Employee', '1953-04-20', 1, '147258369', 'info@show.down', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(47, 5, 6, 'Otmar', '', 'Herbst', '47-otmar-herbst', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(48, 5, 7, 'Elvis', '', 'Demeyer', '48-elvis-demeyer', 'Employee', '1954-05-01', 2, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(49, 5, 8, 'Karsten', '', 'Joslin', '49-karsten-joslin', 'Employee', '1959-12-05', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(50, 5, 9, 'Jeong', '', 'Reistad', '50-jeong-reistad', 'Employee', '1986-01-08', 2, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(51, 5, 10, 'Arif', '', 'Merlo', '51-arif-merlo', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1);


-- --------------------------------------------------------

--
-- Dumping data for table `jos_profiles_offices`
--

INSERT IGNORE INTO `jos_profiles_offices` (`profiles_office_id`, `title`, `slug`, `description`, `address1`, `address2`, `city`, `state`, `postcode`, `country`, `phone`, `fax`, `coordinates`, `created_on`, `created_by`, `modified_on`, `modified_by`, `enabled`) VALUES
(1, 'United States', 'united-states', '', '1 Infinite Loop', '', 'Cupertino', 'CA', '95014', 'US', '147258369', '13456789', '50.9873946,5.0474845', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(2, 'Belgium', 'belgium', '', 'Grote Markt 1', '', 'Brussel', '', '1000', 'BE', '1592648', '2615948', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(3, 'Netherlands', 'netherlands', '', 'Klavermarkt 1', '', 'Den Haag', '', '', 'NL', '147258369', '147258369', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(4, 'Australia', 'australia', '', '', '', 'Sydney', 'QLD', '2000', 'AU', '147258369', '147258369', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(5, 'Cameroon', 'cameroon', '', '', '', '', '', '', 'CM', '147258369', '147258369', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(6, 'Canada', 'canada', '', '', '', 'Quebec', 'QC', 'J1T 1A1', 'CA', '147258369', '147258369', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(7, 'New Zealand', 'new-zealand', '', '', '', 'Wellington', 'GIS', '80549', 'NZ', '147258369', '147258369', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(8, 'Virgin Islands', 'virgin-islands', '', '', '', 'Island', '', '3000', 'VI', '147258369', '147258369', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(9, 'Antarctica', 'antarctica', '', '', '', 'Antarctica', '', '1000', 'AQ', '147258369', '147258369', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(10, 'United Kingdom', 'united-kingdom', '', 'Wall Street 1', '', 'London', '', '1000', 'GB', '147258369', '147258369', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1);

