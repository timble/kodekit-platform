<?php
/**
 * @package        Nooku_Server
 * @subpackage     Files
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library;

/**
 * Files Controller Permission Class
 *
 * @author     Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package    Nooku_Server
 * @subpackage Files
 */
class FilesControllerPermissionImage extends ApplicationControllerPermissionDefault
{
    public function canRender()
    {
        return true;
    }

}