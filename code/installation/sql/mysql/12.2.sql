# Change session table storage engine to InnoDB
# http://nooku.assembla.com/spaces/nooku-server/tickets/190
ALTER TABLE `#__session` ENGINE InnoDB

# Make sure email and username are unique fields
ALTER TABLE  `#__users` ADD UNIQUE  `username` (  `username` )
ALTER TABLE  `#__users` ADD UNIQUE  `email` (  `email` )

# Add UUID field required by identifiable behahvior
ALTER TABLE  `#__users` ADD  `uuid` VARCHAR( 36 ) NOT NULL , ADD UNIQUE (`uuid`)