CREATE TABLE IF NOT EXISTS `#__files_paths` (
  `identifier` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `parameters` TEXT,
  PRIMARY KEY (`identifier`)
) DEFAULT CHARSET=utf8;


INSERT INTO `jos_files_paths` (`identifier`, `path`, `parameters`) VALUES
('files.files', 'images', '{"upload_extensions":"bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls","upload_maxsize":"10485760","allowed_mimetypes":"image\\/jpeg,image\\/gif,image\\/png,imagee\\/bmp,application\\/x-shockwave-flash,application\\/msword,application\\/excel,application\\/pdf,application\\/powerpoint,text\\/plain,application\\/x-zip","illegal_mimetypes":"text\\/html","restrict_uploads":1,"check_mime":1,"allowed_media_usergroup":3}');