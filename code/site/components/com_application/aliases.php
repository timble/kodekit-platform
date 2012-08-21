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

KService::setAlias('application.session', 'koowa:dispatcher.session.default');

KService::setAlias('database'                     , 'com://admin/default.database.adapter.mysqli');
KService::setAlias('koowa:database.adapter.mysqli', 'com://admin/default.database.adapter.mysqli');