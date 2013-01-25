<?php
/**
 * @version     $Id: aliases.php 4448 2012-08-08 22:01:03Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Service Aliases
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */

KService::setAlias('application'           , 'com://site/application.dispatcher.default');
KService::setAlias('application.components', 'com://admin/application.database.rowset.components');
KService::setAlias('application.languages' , 'com://admin/application.database.rowset.languages');
KService::setAlias('application.pages'     , 'com://site/application.database.rowset.pages');
KService::setAlias('application.modules'   , 'com://site/application.database.rowset.modules');

KService::setAlias('koowa:database.adapter.mysql', 'com://admin/application.database.adapter.mysql');
