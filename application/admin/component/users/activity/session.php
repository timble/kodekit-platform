<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Platform\Users;

use Nooku\Library;
use Nooku\Platform\Activities;

/**
 * Users Session Activity
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Users
 */
class ActivitySession extends Activities\ModelEntityActivity
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'format' => '{actor} {action} {application}',
            'objects' => array('application')
        ));

        parent::_initialize($config);
    }

    public function getActivityApplication()
    {
        return $this->_getObject(array('objectName' => $this->application));
    }

    public function getPropertyImage()
    {
        $images = array('add' => 'user', 'delete' => 'off');

        if (isset($images[$this->action])) {
            $image = $images[$this->action];
        } else {
            $image = parent::getPropertyImage();
        }

        return $image;
    }
}