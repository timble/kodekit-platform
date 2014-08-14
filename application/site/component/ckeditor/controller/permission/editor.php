<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Editor Controller Permission Class
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Component\Ckeditor
 */
class CkeditorControllerPermissionEditor extends ApplicationControllerPermissionAbstract
{
    public function canRender()
    {
        return true;
    }
}