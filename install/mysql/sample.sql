SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET @OLD_TIME_ZONE=@@TIME_ZONE, TIME_ZONE='+00:00';
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

UPDATE `pages` SET `link_url` = REPLACE(`link_url`, 'view=articles', 'view=articles&category=1') WHERE `pages_page_id` = 1;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`articles_article_id`, `categories_category_id`, `attachments_attachment_id`, `title`, `slug`, `introtext`, `fulltext`, `published`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `publish_on`, `unpublish_on`, `params`, `ordering`, `description`, `access`, `uuid`)
  VALUES
  (1, 0, 0, 'Mollis', 'mollis', '<p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed diam eget risus varius blandit sit amet non magna. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Nulla vitae elit libero, a pharetra augue. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Nullam quis risus eget urna mollis ornare vel eu leo.</p>\r\n<p>Donec ullamcorper nulla non metus auctor fringilla. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Maecenas sed diam eget risus varius blandit sit amet non magna.</p>', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, UUID()),
  (2, 1, 0, 'Cras', 'cras', '<p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum id ligula porta felis euismod semper. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Etiam porta sem malesuada magna mollis euismod.</p>', '<p>Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec id elit non mi porta gravida at eget metus.</p>\r\n<p>Curabitur blandit tempus porttitor. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Nulla vitae elit libero, a pharetra augue. Maecenas faucibus mollis interdum.</p>', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, UUID()),
  (3, 1, 0, 'Elit Adipiscing', 'elit-adipiscing', '<p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Donec sed odio dui. Nullam id dolor id nibh ultricies vehicula ut id elit. Curabitur blandit tempus porttitor.</p>', '<p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla. Etiam porta sem malesuada magna mollis euismod. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>\r\n<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit sit amet non magna. Donec ullamcorper nulla non metus auctor fringilla.</p>', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 0, UUID()),
  (4, 1, 0, 'Risus Tellus Fermentum', 'risus-tellus-fermentum', '<p>Maecenas faucibus mollis interdum. Donec sed odio dui. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>\r\n<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui. Donec sed odio dui.</p>', '<p>Donec id elit non mi porta gravida at eget metus. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vestibulum id ligula porta felis euismod semper. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>\r\n<p>Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum. Donec ullamcorper nulla non metus auctor fringilla.</p>', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, 0, UUID()),
  (5, 1, 0, 'Nibh Vulputate', 'nibh-vulputate', '<p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Nullam id dolor id nibh ultricies vehicula ut id elit. Vestibulum id ligula porta felis euismod semper. Nullam id dolor id nibh ultricies vehicula ut id elit. Aenean lacinia bibendum nulla sed consectetur. Cras mattis consectetur purus sit amet fermentum.</p>', '<p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod. Nullam quis risus eget urna mollis ornare vel eu leo. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</p>\r\n<p>Aenean lacinia bibendum nulla sed consectetur. Vestibulum id ligula porta felis euismod semper. Cras mattis consectetur purus sit amet fermentum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit. Nullam quis risus eget urna mollis ornare vel eu leo.</p>', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 0, UUID()),
  (6, 1, 0, 'Aenean Pellentesque', 'aenean-pellentesque', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed diam eget risus varius blandit sit amet non magna. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Aenean lacinia bibendum nulla sed consectetur.</p>\r\n<p>Nullam quis risus eget urna mollis ornare vel eu leo. Curabitur blandit tempus porttitor. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Maecenas sed diam eget risus varius blandit sit amet non magna.</p>', '<p>Maecenas faucibus mollis interdum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Donec sed odio dui. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec ullamcorper nulla non metus auctor fringilla.</p>', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, 0, UUID()),
  (7, 2, 0, 'Elit Risus', 'elit-risus', '<p>Nulla vitae elit libero, a pharetra augue. Donec sed odio dui. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Maecenas faucibus mollis interdum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>\r\n\r\n<p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec sed odio dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>\r\n\r\n<p>Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Donec sed odio dui. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Nullam id dolor id nibh ultricies vehicula ut id elit. Sed posuere consectetur est at lobortis. Maecenas faucibus mollis interdum.</p>', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, '2013-06-23 10:46:43', NULL, NULL, NULL, 1, NULL, 0, UUID()),
  (8, 2, 0, 'Cursus', 'cursus', '<p>Curabitur blandit tempus porttitor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur blandit tempus porttitor. Nulla vitae elit libero, a pharetra augue. Nullam quis risus eget urna mollis ornare vel eu leo. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Aenean lacinia bibendum nulla sed consectetur.</p>\r\n<p>Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed diam eget risus varius blandit sit amet non magna. Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, '2013-06-23 10:46:42', NULL, NULL, NULL, 2, NULL, 0, UUID()),
  (9, 2, 0, 'Pharetra Inceptos', 'pharetra-inceptos', '<p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Nullam quis risus eget urna mollis ornare vel eu leo. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum.</p>', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, '2013-06-23 10:46:41', NULL, NULL, NULL, 3, NULL, 0, UUID()),
  (10, 2, 0, 'Malesuada', 'malesuada', '<p>Nulla vitae elit libero, a pharetra augue. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Maecenas sed diam eget risus varius blandit sit amet non magna. Maecenas sed diam eget risus varius blandit sit amet non magna. Curabitur blandit tempus porttitor.</p>', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, '2013-06-23 10:46:40', NULL, NULL, NULL, 4, NULL, 0, UUID());

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categories_category_id`, `parent_id`, `attachments_attachment_id`, `title`, `slug`, `table`, `description`, `published`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `ordering`, `access`, `params`, `uuid`)
  VALUES
  (1, 0, 0, 'Tristique', 'tristique', 'articles', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, 1, 0, '', UUID()),
  (2, 0, 0, 'Tellus', 'tellus', 'articles', '', 1, 1, '2013-07-07 11:15:28', NULL, NULL, NULL, NULL, 2, 0, '', UUID());

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`pages_page_id`, `pages_menu_id`, `users_group_id`, `title`, `slug`, `link_url`, `link_id`, `type`, `published`, `hidden`, `home`, `component`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `access`, `params`, `uuid`)
VALUES
  (29, 1, 0, 'Articles', 'articles', 'component=articles&view=article&id=1', NULL, 'component', 1, 0, 0, 'articles', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'show_create_date=\"0\"\nshow_modify_date=\"0\"\ncommentable=\"0\"\npage_title=\"\"', 'f8338fa3-d131-11e3-ab9f-080027880ca6'),
  (30, 1, 0, 'Blog', 'blog', 'component=articles&view=articles&category=2', NULL, 'component', 1, 0, 0, 'articles', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'articles_per_page=\"3\"\nsort_by=\"newest\"\nshow_create_date=\"0\"\nshow_modify_date=\"0\"\ncommentable=\"0\"\npage_title=\"\"', 'f833926e-d131-11e3-ab9f-080027880ca6'),
  (31, 1, 0, 'Table', 'table', 'component=articles&view=articles&category=2&layout=table', NULL, 'component', 1, 0, 0, 'articles', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'articles_per_page=\"10\"\nsort_by=\"newest\"\nshow_description=\"0\"\nshow_description_image=\"0\"\nshow_date=\"0\"\nshow_create_date=\"0\"\nshow_modify_date=\"0\"\ncommentable=\"0\"\npage_title=\"\"', 'f833936c-d131-11e3-ab9f-080027880ca6'),
  (37, 1, 0, 'Gallery', 'gallery', 'component=files&view=directory&folder=stories&layout=gallery', NULL, 'component', 1, 0, 0, 'files', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'show_folders=\"1\"\nhumanize_filenames=\"1\"\nlimit=\"-1\"\nsort=\"name\"\ndirection=\"asc\"\npage_title=\"\"', 'f8339775-d131-11e3-ab9f-080027880ca6'),
  (38, 1, 0, 'Table', 'table', 'component=files&view=directory&folder=stories&layout=table', NULL, 'component', 1, 0, 0, 'files', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'show_folders=\"1\"\nhumanize_filenames=\"1\"\nlimit=\"-1\"\nsort=\"name\"\ndirection=\"asc\"\npage_title=\"\"', 'f8339841-d131-11e3-ab9f-080027880ca6'),
  (39, 3, 0, 'Login', 'login', 'component=users&view=session', NULL, 'component', 1, 0, 0, 'users', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'description_login_text=\"\"\npage_title=\"\"', 'f8339905-d131-11e3-ab9f-080027880ca6'),
  (40, 4, 0, 'Search', 'search', 'component=articles&view=articles', NULL, 'component', 1, 0, 0, 'articles', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'page_title=\"\"', 'f83399ce-d131-11e3-ab9f-080027880ca6'),
  (41, 3, 0, 'User', 'user', 'component=users&view=user', NULL, 'component', 1, 0, 0, 'users', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'f8339a94-d131-11e3-ab9f-080027880ca6'),
  (42, 1, 0, 'Files', 'files', 'component=articles&view=article&id=1', NULL, 'component', 1, 0, 0, 'articles', NULL, NULL, NULL, NULL, NULL, NULL, 0, '', '027d0f80-a509-4448-b35d-429ea5259895');

--
-- Dumping data for table `pages_orderings`
--

INSERT INTO `pages_closures` (`ancestor_id`, `descendant_id`, `level`)
VALUES
  (29, 29, 0),
  (29, 30, 1),
  (29, 31, 1),
  (30, 30, 0),
  (31, 31, 0),
  (36, 36, 0),
  (37, 37, 0),
  (38, 38, 0),
  (39, 39, 0),
  (40, 40, 0),
  (41, 41, 0),
  (42, 37, 1),
  (42, 38, 1),
  (42, 42, 0);


--
-- Dumping data for table `pages_menus`
--

INSERT INTO `pages_menus` (`pages_menu_id`, `application`, `title`, `slug`, `description`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `uuid`)
  VALUES
  (3, 'site', 'User Menu', 'user-menu', NULL, 1, NULL, NULL, NULL, NULL, NULL, UUID()),
  (4, 'site', 'System Menu', 'system-menu', NULL, 1, NULL, NULL, NULL, NULL, NULL, UUID());

--
-- Dumping data for table `pages_modules`
--

INSERT INTO `pages_modules` (`pages_module_id`, `title`, `content`, `ordering`, `position`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `published`, `name`, `access`, `params`, `component`, `application`, `uuid`)
VALUES
  (2, 'User Menu', '', 4, 'left', 1, '2014-05-01 13:10:29', NULL, NULL, NULL, NULL, 1, 'menu', 1, 'menu_id=3\nshow_title=1\nclass=nav nav-list', 'pages', 'site', 'f833f4bc-d131-11e3-ab9f-080027880ca6'),
  (3, 'Login Form', '', 5, 'left', 1, '2014-05-01 13:10:29', NULL, NULL, NULL, NULL, 1, 'login', 0, 'show_title=1', 'users', 'site', 'f833f7cd-d131-11e3-ab9f-080027880ca6'),
  (4, 'Search', '', 3, 'user4', 1, '2014-05-01 13:10:29', NULL, NULL, NULL, NULL, 1, 'search', 0, 'form_class=navbar-search form-search pull-right\ninput_class=span2 search-query\nitem_id=40', 'articles', 'site', 'f833f87d-d131-11e3-ab9f-080027880ca6'),
  (5, 'Breadcrumbs', '', 1, 'breadcrumb', 1, '2014-05-01 13:10:29', NULL, NULL, NULL, NULL, 1, 'breadcrumbs', 0, 'showHome=1\nhomeText=Home\nshowLast=1', 'pages', 'site', 'f833f90b-d131-11e3-ab9f-080027880ca6'),
  (6, 'Left Menu', '', 6, 'left', 1, '2014-05-01 14:47:37', NULL, NULL, NULL, NULL, 1, 'menu', 0, 'menu_id=1\nshow_title=0\nclass=nav nav-pills nav-stacked\nstart_level=2', 'pages', 'site', 'af9430dc-3bc8-41dc-8e26-ef370e869bbb');

--
-- Dumping data for table `pages_modules`
--

INSERT INTO `pages_modules_pages` (`pages_module_id`, `pages_page_id`)
VALUES
  (3, 1),
  (4, 0),
  (6, 29),
  (6, 30),
  (6, 31),
  (6, 37),
  (6, 38),
  (6, 42);


--
-- Dumping data for table `pages_orderings`
--

INSERT INTO `pages_orderings` (`pages_page_id`, `title`, `custom`)
VALUES
  (29, 00000000001, 00000000002),
  (30, 00000000002, 00000000003),
  (31, 00000000002, 00000000004),
  (36, 00000000004, 00000000005),
  (37, 00000000001, 00000000006),
  (38, 00000000002, 00000000007),
  (39, 00000000001, 00000000001),
  (40, 00000000001, 00000000001),
  (41, 00000000002, 00000000002),
  (42, 00000000002, 00000000003);


--
-- Update creation date to today (this very moment)
--
UPDATE `articles` SET `created_on` = now();
UPDATE `categories` SET `created_on` = now();
UPDATE `articles` SET `created_on` = now();
UPDATE `pages` SET `created_on` = now();
UPDATE `pages_menus` SET `created_on` = now();
UPDATE `pages_modules` SET `created_on` = now();
UPDATE `users` SET `created_on` = now();


SET SQL_MODE=@OLD_SQL_MODE;
SET TIME_ZONE=@OLD_TIME_ZONE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
