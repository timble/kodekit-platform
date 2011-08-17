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

DELETE FROM `#__modules` WHERE `jos_modules`.`id` = 9