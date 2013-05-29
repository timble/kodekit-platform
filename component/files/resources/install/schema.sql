--
-- Table structure for table `files_containers`
--

CREATE TABLE `files_containers` (
  `files_container_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `parameters` text NOT NULL,
  PRIMARY KEY (`files_container_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `files_thumbnails`
--

CREATE TABLE `files_thumbnails` (
  `files_thumbnail_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `files_container_id` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `thumbnail` text NOT NULL,
  PRIMARY KEY (`files_thumbnail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `files_containers` (`files_container_id`, `slug`, `title`, `path`, `parameters`) VALUES
(NULL, 'files-files', 'Images', 'images', '{"thumbnails": true,"maximum_size":"10485760","allowed_extensions": ["bmp", "csv", "doc", "gif", "ico", "jpg", "jpeg", "odg", "odp", "ods", "odt", "pdf", "png", "ppt", "swf", "txt", "xcf", "xls"],"allowed_mimetypes": ["image/jpeg", "image/gif", "image/png", "image/bmp", "application/x-shockwave-flash", "application/msword", "application/excel", "application/pdf", "application/powerpoint", "text/plain", "application/x-zip"]}');