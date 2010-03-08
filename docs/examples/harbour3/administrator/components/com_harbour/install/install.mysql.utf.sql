CREATE TABLE IF NOT EXISTS `#__harbour_boats` (
  `harbour_boat_id` SERIAL,
  `name` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL default '1', 
  `description` text NULL COMMENT = '@Filter("html, tidy")',
  `created_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL default 0,
  `modified_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL default 0,
  `locked_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL default 0
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `#__harbour_boats` (`name`) VALUES
('Herald of Free Enterprise'),
('Titanic'),
('Flying Dutchman');

