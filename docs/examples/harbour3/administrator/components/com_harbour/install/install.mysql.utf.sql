CREATE TABLE IF NOT EXISTS `#__harbour_boats` (
  `harbour_boat_id` SERIAL,
  `name` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL default '1', 
  `description` text NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__harbour_boats` (`name`) VALUES
('Herald of Free Enterprise'),
('Titanic'),
('Flying Dutchman');

