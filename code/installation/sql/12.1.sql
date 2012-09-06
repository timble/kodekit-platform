# --------------------------------------------------------
# Diff Joomla 1.5 to alpha3

CREATE TABLE `#__versions_revisions` (
  `table` varchar(64) NOT NULL,
  `row` bigint(20) unsigned NOT NULL,
  `revision` bigint(20) unsigned NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `data` longtext NOT NULL COMMENT '@Filter("json")',
  `status` varchar(100) NOT NULL,
  PRIMARY KEY  (`table`,`row`,`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
# Diff alpha3 to alpha4

DROP TABLE IF EXISTS `#__files_paths`;

CREATE TABLE IF NOT EXISTS `#__files_containers` (
  `files_container_id` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `parameters` TEXT,
  PRIMARY KEY (`files_container_id`)
) DEFAULT CHARSET=utf8;


INSERT INTO `#__files_containers` (`files_container_id`, `path`, `parameters`) VALUES
('com_files.files', 'images', '{"upload_extensions":"bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls","upload_maxsize":"10485760","allowed_mimetypes":"image\\/jpeg,image\\/gif,image\\/png,imagee\\/bmp,application\\/x-shockwave-flash,application\\/msword,application\\/excel,application\\/pdf,application\\/powerpoint,text\\/plain,application\\/x-zip","illegal_mimetypes":"text\\/html","restrict_uploads":1,"check_mime":1,"allowed_media_usergroup":3}');

CREATE TABLE IF NOT EXISTS `#__files_thumbnails` (
  `files_thumbnail_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `files_container_id` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `thumbnail` text NOT NULL,
  PRIMARY KEY (`files_thumbnail_id`)
) DEFAULT CHARSET=utf8;


# --------------------------------------------------------
# com_activities schema changes

CREATE TABLE IF NOT EXISTS `#__activities_activities` (
	`activities_activity_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`application` VARCHAR(10) NOT NULL DEFAULT '',
	`type` VARCHAR(3) NOT NULL DEFAULT '',
	`package` VARCHAR(50) NOT NULL DEFAULT '',
	`name` VARCHAR(50) NOT NULL DEFAULT '',
	`action` VARCHAR(50) NOT NULL DEFAULT '',
	`row` BIGINT NOT NULL DEFAULT '0',
	`title` VARCHAR(255) NOT NULL DEFAULT '',
	`status` varchar(100) NOT NULL,
	`created_on` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY(`activities_activity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;