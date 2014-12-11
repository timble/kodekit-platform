<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Users Session Activity
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Users
 */
class UsersActivitySession extends ActivitiesModelEntityActivity
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array('format' => '{actor} {action} {application}', 'objects' => array('application')));
        parent::_initialize($config);
    }

    public function getActivityApplication()
    {
        return $this->_getObject(array('objectName' => $this->application));
    }
}