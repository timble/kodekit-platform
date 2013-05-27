<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Directory Database Row Class
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Component\Files
 */
class DatabaseRowDirectory extends DatabaseRowFolder
{
    public function isLockable()
    {
        return false;
    }
}