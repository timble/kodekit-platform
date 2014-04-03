-- --------------------------------------------------------

--
-- Table structure for table `versions_revisions`
--

CREATE TABLE `revisions` (
  `table` varchar(64) NOT NULL,
  `row` bigint(20) unsigned NOT NULL,
  `revision` bigint(20) unsigned NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `data` longtext NOT NULL COMMENT '@Filter("json")',
  `status` varchar(100) NOT NULL,
  `uuid` char(36) NOT NULL,
  PRIMARY KEY  (`table`,`row`,`revision`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;