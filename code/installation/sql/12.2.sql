-- Change session table storage engine to InnoDB
-- http://nooku.assembla.com/spaces/nooku-server/tickets/190
ALTER TABLE `#__session` ENGINE InnoDB;

-- Add UUID field required by identifiable behahvior
ALTER TABLE `#__users` ADD `uuid` CHAR(36) NOT NULL AFTER `id`, ADD UNIQUE (`uuid`);