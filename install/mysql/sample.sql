SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET @OLD_TIME_ZONE=@@TIME_ZONE, TIME_ZONE='+00:00';
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

UPDATE `pages` SET `link_url` = REPLACE(`link_url`, 'view=articles', 'view=articles&category=1') WHERE `pages_page_id` = 1;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`articles_article_id`, `categories_category_id`, `attachments_attachment_id`, `title`, `slug`, `introtext`, `fulltext`, `published`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `publish_on`, `unpublish_on`, `params`, `ordering`, `description`, `access`)
  VALUES
  (1, 0, 0, 'Mollis', 'mollis', '<p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed diam eget risus varius blandit sit amet non magna. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Nulla vitae elit libero, a pharetra augue. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Nullam quis risus eget urna mollis ornare vel eu leo.</p>\r\n<p>Donec ullamcorper nulla non metus auctor fringilla. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Maecenas sed diam eget risus varius blandit sit amet non magna.</p>', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0),
  (2, 1, 0, 'Cras', 'cras', '<p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum id ligula porta felis euismod semper. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Etiam porta sem malesuada magna mollis euismod.</p>', '<p>Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec id elit non mi porta gravida at eget metus.</p>\r\n<p>Curabitur blandit tempus porttitor. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Nulla vitae elit libero, a pharetra augue. Maecenas faucibus mollis interdum.</p>', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0),
  (3, 1, 0, 'Elit Adipiscing', 'elit-adipiscing', '<p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Donec sed odio dui. Nullam id dolor id nibh ultricies vehicula ut id elit. Curabitur blandit tempus porttitor.</p>', '<p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla. Etiam porta sem malesuada magna mollis euismod. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>\r\n<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit sit amet non magna. Donec ullamcorper nulla non metus auctor fringilla.</p>', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 0),
  (4, 1, 0, 'Risus Tellus Fermentum', 'risus-tellus-fermentum', '<p>Maecenas faucibus mollis interdum. Donec sed odio dui. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>\r\n<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui. Donec sed odio dui.</p>', '<p>Donec id elit non mi porta gravida at eget metus. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vestibulum id ligula porta felis euismod semper. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>\r\n<p>Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum. Donec ullamcorper nulla non metus auctor fringilla.</p>', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, 0),
  (5, 1, 0, 'Nibh Vulputate', 'nibh-vulputate', '<p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Nullam id dolor id nibh ultricies vehicula ut id elit. Vestibulum id ligula porta felis euismod semper. Nullam id dolor id nibh ultricies vehicula ut id elit. Aenean lacinia bibendum nulla sed consectetur. Cras mattis consectetur purus sit amet fermentum.</p>', '<p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod. Nullam quis risus eget urna mollis ornare vel eu leo. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</p>\r\n<p>Aenean lacinia bibendum nulla sed consectetur. Vestibulum id ligula porta felis euismod semper. Cras mattis consectetur purus sit amet fermentum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit. Nullam quis risus eget urna mollis ornare vel eu leo.</p>', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 0),
  (6, 1, 0, 'Aenean Pellentesque', 'aenean-pellentesque', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed diam eget risus varius blandit sit amet non magna. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Aenean lacinia bibendum nulla sed consectetur.</p>\r\n<p>Nullam quis risus eget urna mollis ornare vel eu leo. Curabitur blandit tempus porttitor. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Maecenas sed diam eget risus varius blandit sit amet non magna.</p>', '<p>Maecenas faucibus mollis interdum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Donec sed odio dui. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec ullamcorper nulla non metus auctor fringilla.</p>', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, 0),
  (7, 2, 0, 'Elit Risus', 'elit-risus', '<p>Nulla vitae elit libero, a pharetra augue. Donec sed odio dui. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Maecenas faucibus mollis interdum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>\r\n\r\n<p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec sed odio dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>\r\n\r\n<p>Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Donec sed odio dui. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Nullam id dolor id nibh ultricies vehicula ut id elit. Sed posuere consectetur est at lobortis. Maecenas faucibus mollis interdum.</p>', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, '2013-06-23 10:46:43', NULL, NULL, NULL, 1, NULL, 0),
  (8, 2, 0, 'Cursus', 'cursus', '<p>Curabitur blandit tempus porttitor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur blandit tempus porttitor. Nulla vitae elit libero, a pharetra augue. Nullam quis risus eget urna mollis ornare vel eu leo. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Aenean lacinia bibendum nulla sed consectetur.</p>\r\n<p>Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed diam eget risus varius blandit sit amet non magna. Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, '2013-06-23 10:46:42', NULL, NULL, NULL, 2, NULL, 0),
  (9, 2, 0, 'Pharetra Inceptos', 'pharetra-inceptos', '<p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Nullam quis risus eget urna mollis ornare vel eu leo. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum.</p>', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, '2013-06-23 10:46:41', NULL, NULL, NULL, 3, NULL, 0),
  (10, 2, 0, 'Malesuada', 'malesuada', '<p>Nulla vitae elit libero, a pharetra augue. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Maecenas sed diam eget risus varius blandit sit amet non magna. Maecenas sed diam eget risus varius blandit sit amet non magna. Curabitur blandit tempus porttitor.</p>', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, '2013-06-23 10:46:40', NULL, NULL, NULL, 4, NULL, 0);

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categories_category_id`, `parent_id`, `attachments_attachment_id`, `title`, `slug`, `table`, `description`, `published`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `ordering`, `access`, `params`)
  VALUES
  (1, 0, 0, 'Tristique', 'tristique', 'articles', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, 1, 0, ''),
  (2, 0, 0, 'Tellus', 'tellus', 'articles', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, 2, 0, ''),
  (3, 0, 0, 'Tellus Mollis', 'tellus-mollis', 'contacts', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, 1, 0, '');

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`contacts_contact_id`, `name`, `slug`, `position`, `address`, `suburb`, `state`, `country`, `postcode`, `telephone`, `fax`, `misc`, `email_to`, `published`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `ordering`, `params`, `categories_category_id`, `access`, `mobile`)
  VALUES
  (1, 'Dolor Sit', 'dolor-sit', 'Porta Ornare', 'Fermentum 1', 'Condimentum', 'Lorem', 'Pharetra', '61803', '161803', '618', '<p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>', 'dolor@pellentesque.com', 1, 1, NULL, NULL, NULL, NULL, NULL, 1, 'show_email=\"0\"\nshow_email_form=\"1\"\nallow_vcard=\"0\"', 3, 0, '39887');

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`pages_page_id`, `pages_menu_id`, `users_group_id`, `title`, `slug`, `link_url`, `link_id`, `type`, `published`, `hidden`, `home`, `extensions_extension_id`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `access`, `params`)
  VALUES
  (29, 1, 0, 'Article', 'article', 'option=com_articles&view=article&id=1', NULL, 'component', 1, 0, 0, 20, 1, NULL, NULL, NULL, NULL, NULL, 0, 'show_create_date=\"0\"\nshow_modify_date=\"0\"\npage_title=\"\"'),
  (30, 1, 0, 'Articles Blog', 'blog', 'option=com_articles&view=articles&category=2', NULL, 'component', 1, 0, 0, 20, 1, NULL, NULL, NULL, NULL, NULL, 0, 'articles_per_page=\"3\"\nsort_by=\"newest\"\nshow_create_date=\"0\"\nshow_modify_date=\"0\"\npage_title=\"\"'),
  (31, 1, 0, 'Articles Table', 'table', 'option=com_articles&view=articles&category=2&layout=table', NULL, 'component', 1, 0, 0, 20, 1, NULL, NULL, NULL, NULL, NULL, 0, 'articles_per_page=\"3\"\nsort_by=\"newest\"\nshow_create_date=\"0\"\nshow_modify_date=\"0\"\npage_title=\"\"'),
  (32, 1, 0, 'Malesuada', 'malesuada', 'option=com_articles&view=article&id=10', NULL, 'component', 1, 0, 0, 20, 1, NULL, NULL, NULL, NULL, NULL, 0, 'show_create_date=\"0\"\nshow_modify_date=\"0\"\npage_title=\"\"'),
  (33, 1, 0, 'Pharetra Inceptos', 'pharetra-inceptos', 'option=com_articles&view=article&id=9', NULL, 'component', 1, 0, 0, 20, 1, NULL, NULL, NULL, NULL, NULL, 0, 'show_create_date=\"0\"\nshow_modify_date=\"0\"\npage_title=\"\"'),
  (34, 1, 0, 'Cursus', 'cursus', 'option=com_articles&view=article&id=8', NULL, 'component', 1, 0, 0, 20, 1, NULL, NULL, NULL, NULL, NULL, 0, 'show_create_date=\"0\"\nshow_modify_date=\"0\"\npage_title=\"\"'),
  (35, 1, 0, 'Elit Risus', 'elit-risus', 'option=com_articles&view=article&id=7', NULL, 'component', 1, 0, 0, 20, 1, NULL, NULL, NULL, NULL, NULL, 0, 'show_create_date=\"0\"\nshow_modify_date=\"0\"\npage_title=\"\"'),
  (36, 1, 0, 'Contacts', 'contacts', 'option=com_contacts&view=contacts&category=3', NULL, 'component', 1, 0, 0, 7, 1, NULL, NULL, NULL, NULL, NULL, 0, 'show_telephone=\"1\"\npage_title=\"\"'),
  (37, 1, 0, 'Files Gallery', 'files-gallery', 'option=com_files&view=directory&folder=stories&layout=gallery', NULL, 'component', 1, 0, 0, 19, 1, NULL, NULL, NULL, NULL, NULL, 0, 'show_folders=\"1\"\nhumanize_filenames=\"1\"\nlimit=\"-1\"\nsort=\"name\"\ndirection=\"asc\"\npage_title=\"\"'),
  (38, 1, 0, 'Files Table', 'files-table', 'option=com_files&view=directory&folder=stories&layout=table', NULL, 'component', 1, 0, 0, 19, 1, NULL, NULL, NULL, NULL, NULL, 0, 'show_folders=\"1\"\nhumanize_filenames=\"1\"\nlimit=\"-1\"\nsort=\"name\"\ndirection=\"asc\"\npage_title=\"\"'),
  (39, 3, 0, 'Login', 'login', 'option=com_users&view=session', NULL, 'component', 1, 0, 0, 31, 1, NULL, NULL, NULL, NULL, NULL, 0, 'description_login_text=\"\"\npage_title=\"\"'),
  (40, 4, 0, 'Search', 'search', 'option=com_articles&view=articles', NULL, 'component', 1, 0, 0, 20, 1, NULL, NULL, NULL, NULL, NULL, 0, 'page_title=\"\"');

--
-- Dumping data for table `pages_orderings`
--

INSERT INTO `pages_closures` (`ancestor_id`, `descendant_id`, `level`)
  VALUES
  (29, 29, 0),
  (30, 30, 0),
  (30, 32, 1),
  (30, 33, 1),
  (30, 34, 1),
  (30, 35, 1),
  (31, 31, 0),
  (32, 32, 0),
  (33, 33, 0),
  (34, 34, 0),
  (35, 35, 0),
  (36, 36, 0),
  (37, 37, 0),
  (38, 38, 0),
  (39, 39, 0),
  (40, 40, 0);

--
-- Dumping data for table `pages_menus`
--

INSERT INTO `pages_menus` (`pages_menu_id`, `application`, `title`, `slug`, `description`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`)
  VALUES
  (3, 'site', 'User Menu', 'user-menu', NULL, 1, NULL, NULL, NULL, NULL, NULL),
  (4, 'site', 'System Menu', 'system-menu', NULL, 1, NULL, NULL, NULL, NULL, NULL);

--
-- Dumping data for table `pages_modules`
--

INSERT INTO `pages_modules` (`pages_module_id`, `title`, `content`, `ordering`, `position`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `published`, `name`, `access`, `params`, `extensions_extension_id`, `application`)
  VALUES
  (2, 'User Menu', '', 18, 'left', 1, NULL, NULL, NULL, NULL, NULL, 1, 'mod_menu', 1, 'menu_id=3\nshow_title=1\nclass=nav nav-list', 25, 'site'),
  (3, 'Login Form', '', 21, 'left', 1, NULL, NULL, NULL, NULL, NULL, 1, 'mod_login', 0, 'show_title=1', 31, 'site'),
  (4, 'Search', '', 10, 'user4', 1, NULL, NULL, NULL, NULL, NULL, 1, 'mod_search', 0, 'form_class=navbar-search form-search pull-right\ninput_class=span2 search-query\nitem_id=40', 20, 'site'),
  (5, 'Breadcrumbs', '', 1, 'breadcrumb', 1, NULL, NULL, NULL, NULL, NULL, 1, 'mod_breadcrumbs', 0, 'showHome=1\nhomeText=Home\nshowLast=1', 25, 'site');

--
-- Dumping data for table `pages_modules`
--

INSERT INTO `pages_modules_pages` (`pages_module_id`, `pages_page_id`)
  VALUES
  (4, 0),
  (3, 1);

--
-- Dumping data for table `pages_orderings`
--

INSERT INTO `pages_orderings` (`pages_page_id`, `title`, `custom`)
  VALUES
  (29, 00000000001, 00000000002),
  (30, 00000000002, 00000000003),
  (31, 00000000003, 00000000004),
  (32, 00000000003, 00000000001),
  (33, 00000000004, 00000000002),
  (34, 00000000001, 00000000003),
  (35, 00000000002, 00000000004),
  (36, 00000000004, 00000000005),
  (37, 00000000005, 00000000006),
  (38, 00000000006, 00000000007),
  (39, 00000000001, 00000000001),
  (40, 00000000001, 00000000001);

--
-- Update creation date to today (this very moment)
--
UPDATE `articles` SET `created_on` = now();
UPDATE `categories` SET `created_on` = now();
UPDATE `contacts` SET `created_on` = now();
UPDATE `articles` SET `created_on` = now();
UPDATE `pages` SET `created_on` = now();
UPDATE `pages_menus` SET `created_on` = now();
UPDATE `pages_modules` SET `created_on` = now();
UPDATE `users` SET `created_on` = now();


SET SQL_MODE=@OLD_SQL_MODE;
SET TIME_ZONE=@OLD_TIME_ZONE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;