CREATE TABLE `#__attachments_attachments` (
  `attachments_attachment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `container` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`attachments_attachment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE `#__attachments_relations` (
  `attachments_attachment_id` int(10) unsigned NOT NULL,
  `table` varchar(64) NOT NULL,
  `row` int(10) unsigned NOT NULL,
  KEY `attachments_attachment_id` (`attachments_attachment_id`),
  CONSTRAINT `jos_attachments_relations_ibfk_1` FOREIGN KEY (`attachments_attachment_id`) REFERENCES `jos_attachments_attachments` (`attachments_attachment_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;