CREATE TABLE IF NOT EXISTS `#__files_paths` (
  `identifier` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `parameters` varchar(1024) NOT NULL,
  UNIQUE KEY (`identifier`)
) DEFAULT CHARSET=utf8;