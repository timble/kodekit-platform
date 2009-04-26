CREATE TABLE IF NOT EXISTS `#__harbour_boats` (
  `harbour_boat_id` SERIAL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__harbour_boats` (`name`) VALUES
('Herald of Free Enterprise'),
('Titanic');

