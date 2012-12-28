<?php
/**
 * @version     $Id: executable.php 5735 2012-11-09 12:47:20Z arunasmazeika $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Controller Permissions Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationControllerPermissionDefault extends ComDefaultControllerPermissionDefault
{
    public function canRender()
    {
        return true;
    }
}