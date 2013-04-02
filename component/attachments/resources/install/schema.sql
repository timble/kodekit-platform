
-- --------------------------------------------------------
--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `attachments_attachment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `container` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `description` text,
  `created_by` int(11) unsigned DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_by` int(11) unsigned DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `locked_by` int(11) unsigned DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  PRIMARY KEY (`attachments_attachment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table structure for table `attachments_relations`
--

CREATE TABLE `attachments_relations` (
  `attachments_attachment_id` int(10) unsigned NOT NULL,
  `table` varchar(64) NOT NULL,
  `row` int(10) unsigned NOT NULL,
  KEY `attachments_attachment_id` (`attachments_attachment_id`),
  CONSTRAINT `attachments_relations_ibfk_1` FOREIGN KEY (`attachments_attachment_id`) REFERENCES `attachments_attachments` (`attachments_attachment_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;