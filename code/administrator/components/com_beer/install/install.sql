

CREATE TABLE IF NOT EXISTS `#__beer_departments` (
  `beer_department_id` SERIAL,
  `title` varchar(250) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL default 0,
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default 0,
  `enabled` tinyint(1) SIGNED  NOT NULL default 1
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
  `coordinates` varchar(250) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL default 0,
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default 0,
  `enabled` tinyint(1) SIGNED NOT NULL default 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `#__beer_people` (
  `beer_person_id` SERIAL,
  `beer_department_id` bigint(20) UNSIGNED NOT NULL,
  `beer_office_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NULL COMMENT 'Joomla user id',
  `firstname` varchar(45) NOT NULL,
  `middlename` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `position` varchar(45) NOT NULL,
  `birthday` date NOT NULL default '0000-00-00',
  `gender` tinyint(3) NOT NULL,
  `mobile` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `bio` text NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_by` bigint(20) UNSIGNED NOT NULL default 0,
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` bigint(20) UNSIGNED NOT NULL default 0,
  `enabled` tinyint(1) SIGNED NOT NULL default 1,
  KEY `department` (`beer_department_id`),
  KEY `office` (`beer_office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE OR REPLACE VIEW #__beer_viewpeople AS 
SELECT p.*, 
	CONCAT_WS(' ', p.`firstname`, p.`middlename`, p.`lastname`) AS name,
	IF(d.enabled < 1, CONCAT('[', d.title, ']'), d.title) AS department,
	d.enabled AS department_enabled, 
	IF(o.enabled < 1, CONCAT('[', o.title, ']'), o.title) AS office,
	o.enabled AS office_enabled,
	CONCAT_WS('\n', o.address1, o.address2, CONCAT_WS(' ', o.city, o.state, o.postcode), o.country) AS address,
	o.phone, o.address1, o.address2, o.city, o.state, o.postcode, o.country, o.fax, o.coordinates,
	CONCAT(p.beer_person_id, ':', p.alias) AS slug,
	CONCAT(d.beer_department_id, ':', d.alias) AS department_slug,
	CONCAT(o.beer_office_id, ':', o.alias) AS office_slug	
FROM #__beer_people AS p
LEFT JOIN #__beer_departments AS d ON d.beer_department_id = p.beer_department_id
LEFT JOIN #__beer_offices AS o ON o.beer_office_id = p.beer_office_id;


CREATE OR REPLACE VIEW #__beer_viewdepartments AS 
SELECT d.*, 
	COUNT( DISTINCT p.beer_person_id ) AS people,
	CONCAT(d.beer_department_id, ':', d.alias) AS slug
FROM #__beer_departments AS d
LEFT JOIN #__beer_people AS p ON p.beer_department_id = d.beer_department_id AND p.enabled > 0
GROUP BY d.beer_department_id;


CREATE OR REPLACE VIEW #__beer_viewoffices AS 
SELECT o.*, 
	COUNT( DISTINCT p.beer_person_id ) AS people,
	CONCAT_WS('\n', address1, address2, CONCAT_WS(' ', city, state, postcode), country) AS address,
	CONCAT(o.beer_office_id, ':', o.alias) AS slug
FROM #__beer_offices AS o
LEFT JOIN #__beer_people AS p ON p.beer_office_id = o.beer_office_id AND p.enabled > 0
GROUP BY o.beer_office_id;

CREATE OR REPLACE VIEW #__beer_firstnameletters AS
SELECT
	DISTINCT LEFT(tbl.firstname, 1) AS beer_firstnameletter_id
FROM #__beer_people AS tbl
ORDER BY tbl.firstname;

CREATE OR REPLACE VIEW #__beer_lastnameletters AS
SELECT
	DISTINCT LEFT(tbl.lastname, 1) AS beer_lastnameletter_id
FROM #__beer_people AS tbl
ORDER BY tbl.lastname;

-- --------------------------------------------------------

--
-- Dumping data for table `#__beer_departments`
--

INSERT INTO `#__beer_departments` (`beer_department_id`, `title`, `alias`, `description`, `created`, `created_by`, `modified`, `modified_by`, `enabled`) VALUES
(1, 'Marketing', 'marketing', 'The world-class marketing team at Showdown is focused on leading-edge hardware and software that define the solutions that customers want, prompting the competition to emulate us. As the only company that designs the hardware, the software, and the operating system, we stand alone in our ability to innovate beyond the status quo. Part of what drives this innovation is our challenging and creative environment and the fierce dedication and talent of our team. In marketing, you have the unique opportunity to work on revolutionary products from concept to launch with the best creative minds in the industry.', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(2, 'Sales', 'sales', 'Showdown is committed to delivering the finest and most innovative computing solutions to students, educators, consumers, businesses, and creative professionals around the world. On the Sales team, our primary focus is to drive revenue for hardware, software, and professional services. One of the benefits of selling Showdown products is that they are completely integrated platforms. We focus on selling the value inherent in the complete product, rather than just individual boxes, and giving our customers a solution that address their needs. Our high-performance sales teams constantly strive to increase customer satisfaction and grow our market share.', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(3, 'Finance', 'finance', 'The Finance department is an integral part of Showdown\\''s success, supporting the growth and change of all functional areas of the company with flexibility and integrity. Having a team of talented thinkers who can balance a detail-oriented and quantifiable function within a dynamic, forward-thinking organization enables Showdown to create products that defy the status quo. The Finance department at Showdown offers opportunities for career development and growth as varied and engaging as the products we build.', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(4, 'Applications', 'applications', 'The Applications division develops simply awesome consumer and professional applications for digital media and personal productivity. Our people are both super-technical and abundantly creative. They\\''re engineers and marketers but they\\''re also musicians, filmmakers, photographers, composers and digital artists.', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(5, 'Information Systems & Technology', 'information-systems--technology', 'Information Systems & Technology (IS&T) drives Showdown\\''s corporate systems, retail systems and other key infrastructure. IS&T offers you tremendous opportunities to work on a wide variety of projects, from developing new tools, to implement standards-based applications, to managing Showdown\\''s email and telephone systems. The team uses industry-standard technologies such as SAP, PeopleSoft, Oracle and AIX, but also creates customized programs when off-the-shelf products don\\''t meet Showdown\\''s needs. If a global IT/MIS shop like no other is the challenge you\\''re looking for, we\\''re looking for you.', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1);


-- --------------------------------------------------------

--
-- Dumping data for table `#__beer_people`
--

INSERT INTO `#__beer_people` (`beer_person_id`, `beer_department_id`, `beer_office_id`, `firstname`, `middlename`, `lastname`, `alias`, `position`, `birthday`, `gender`, `mobile`, `email`, `bio`, `created`, `created_by`, `modified`, `modified_by`, `enabled`) VALUES
(2, 1, 1, 'Eberhardt', '', 'Terkki', 'eberhardt_terkki', 'Employee', '1964-06-02', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(3, 1, 2, 'Bamford', '', 'Parto', 'bamford_parto', 'Employee', '1959-11-30', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:13', 62, '1970-01-01 01:00:00', 0, 1),
(4, 1, 3, 'Chirstian', '', 'Koblick', 'chirstian_koblick', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:18', 62, '1970-01-01 01:00:00', 0, 1),
(5, 1, 4, 'Kyoichi', '', 'Maliniak', 'kyoichi_maliniak', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:22', 62, '1970-01-01 01:00:00', 0, 1),
(6, 1, 5, 'Tzvetan', '', 'Zielinski', 'tzvetan_zielinski', 'Employee', '1953-04-20', 1, '147258369', 'info@show.down', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(7, 1, 6, 'Saniya', '', 'Kalloufi', 'saniya_kalloufi', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(8, 1, 7, 'Sumant', '', 'Peac', 'sumant_peac', 'Employee', '1954-05-01', 2, '147258369', 'info@down.show', '', '2009-07-05 23:24:51', 62, '1970-01-01 01:00:00', 0, 1),
(9, 1, 8, 'Duangkaew', '', 'Piveteau', 'duangkaew_piveteau', 'Employee', '1959-12-05', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:26', 62, '1970-01-01 01:00:00', 0, 1),
(10, 1, 9, 'Mary', '', 'Sluis', 'mary_sluis', 'Employee', '1986-01-08', 2, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(11, 1, 10, 'Patricio', '', 'Bridgland', 'patricio_bridgland', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(12, 2, 1, 'Bezalel', '', 'Simmel', 'bezalel_simmel', 'Employee', '1964-06-02', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(13, 2, 2, 'Berni', '', 'Genin', 'berni_genin', 'Employee', '1959-11-30', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:31', 62, '1970-01-01 01:00:00', 0, 1),
(14, 2, 3, 'Guoxiang', '', 'Nooteboom', 'guoxiang_nooteboom', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:36', 62, '1970-01-01 01:00:00', 0, 1),
(15, 2, 4, 'Cristinel', '', 'Bouloucos', 'cristinel_bouloucos', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:43', 62, '1970-01-01 01:00:00', 0, 1),
(16, 2, 5, 'Kazuhide', '', 'Peha', 'kazuhide_peha', 'Employee', '1953-04-20', 1, '147258369', 'info@show.down', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(17, 2, 6, 'Lillian', '', 'Haddadi', 'lillian_haddadi', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(18, 2, 7, 'Mayuko', '', 'Warwick', 'mayuko_warwick', 'Employee', '1954-05-01', 2, '147258369', 'info@down.show', '', '2009-07-05 23:24:59', 62, '1970-01-01 01:00:00', 0, 1),
(19, 2, 8, 'Ramzi', '', 'Erde', 'ramzi_erde', 'Employee', '1959-12-05', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:49', 62, '1970-01-01 01:00:00', 0, 1),
(20, 2, 9, 'Shahaf', '', 'Famili', 'shahaf_famili', 'Employee', '1986-01-08', 2, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(21, 2, 10, 'Bojan', '', 'Montemayor', 'bojan_montemayor', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(22, 3, 1, 'Suzette', '', 'Pettey', 'suzette_pettey', 'Employee', '1964-06-02', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(23, 3, 2, 'Prasadram', '', 'Heyers', 'prasadram_heyers', 'Employee', '1959-11-30', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:54', 62, '1970-01-01 01:00:00', 0, 1),
(24, 3, 3, 'Yongqiao', '', 'Berztiss', 'yongqiao_berztiss', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:33:06', 62, '1970-01-01 01:00:00', 0, 1),
(25, 3, 4, 'Divier', '', 'Reistad', 'divier_reistad', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:59', 62, '1970-01-01 01:00:00', 0, 1),
(26, 3, 5, 'Domenick', '', 'Tempesti', 'domenick_tempesti', 'Employee', '1953-04-20', 1, '147258369', 'info@show.down', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(27, 3, 6, 'Otmar', '', 'Herbst', 'otmar_herbst', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(28, 3, 7, 'Elvis', '', 'Demeyer', 'elvis_demeyer', 'Employee', '1954-05-01', 2, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(29, 3, 8, 'Karsten', '', 'Joslin', 'karsten_joslin', 'Employee', '1959-12-05', 1, '147258369', 'info@down.show', '', '2009-07-05 23:32:07', 62, '1970-01-01 01:00:00', 0, 1),
(30, 3, 9, 'Jeong', '', 'Reistad', 'jeong_reistad', 'Employee', '1986-01-08', 2, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(31, 3, 10, 'Arif', '', 'Merlo', 'arif_merlo', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(32, 4, 1, 'Bader', '', 'Swan', 'bader_swan', 'Employee', '1964-06-02', 1, '147258369', 'info@down.show', '', '0000-00-00 00:00:00', 62, '1970-01-01 01:00:00', 0, 1),
(33, 4, 2, 'Berni', '', 'Genin', 'berni_genin', 'Employee', '1959-11-30', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(34, 4, 3, 'Guoxiang', '', 'Nooteboom', 'guoxiang_nooteboom', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(35, 4, 4, 'Cristinel', '', 'Bouloucos', 'cristinel_bouloucos', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(36, 4, 5, 'Kazuhide', '', 'Peha', 'kazuhide_peha', 'Employee', '1953-04-20', 1, '147258369', 'info@show.down', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(37, 4, 6, 'Lillian', '', 'Haddadi', 'lillian_haddadi', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(38, 4, 7, 'Mayuko', '', 'Warwick', 'mayuko_warwick', 'Employee', '1954-05-01', 2, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(39, 4, 8, 'Ramzi', '', 'Erde', 'ramzi_erde', 'Employee', '1959-12-05', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(40, 4, 9, 'Shahaf', '', 'Famili', 'shahaf_famili', 'Employee', '1986-01-08', 2, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(41, 4, 10, 'Bojan', '', 'Montemayor', 'bojan_montemayor', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(42, 5, 1, 'Suzette', '', 'Pettey', 'suzette_pettey', 'Employee', '1964-06-02', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(43, 5, 2, 'Prasadram', '', 'Heyers', 'prasadram_heyers', 'Employee', '1959-11-30', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(44, 5, 3, 'Yongqiao', '', 'Berztiss', 'yongqiao_berztiss', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(45, 5, 4, 'Divier', '', 'Reistad', 'divier_reistad', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(46, 5, 5, 'Domenick', '', 'Tempesti', 'domenick_tempesti', 'Employee', '1953-04-20', 1, '147258369', 'info@show.down', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(47, 5, 6, 'Otmar', '', 'Herbst', 'otmar_herbst', 'Employee', '1953-04-20', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(48, 5, 7, 'Elvis', '', 'Demeyer', 'elvis_demeyer', 'Employee', '1954-05-01', 2, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(49, 5, 8, 'Karsten', '', 'Joslin', 'karsten_joslin', 'Employee', '1959-12-05', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(50, 5, 9, 'Jeong', '', 'Reistad', 'jeong_reistad', 'Employee', '1986-01-08', 2, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1),
(51, 5, 10, 'Arif', '', 'Merlo', 'arif_merlo', 'Employee', '1954-05-01', 1, '147258369', 'info@down.show', '', '2009-07-05 23:35:01', 62, '1970-01-01 01:00:00', 0, 1);


-- --------------------------------------------------------

--
-- Dumping data for table `#__beer_offices`
--

INSERT INTO `#__beer_offices` (`beer_office_id`, `title`, `alias`, `description`, `address1`, `address2`, `city`, `state`, `postcode`, `country`, `phone`, `fax`, `coordinates`, `created`, `created_by`, `modified`, `modified_by`, `enabled`) VALUES
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

