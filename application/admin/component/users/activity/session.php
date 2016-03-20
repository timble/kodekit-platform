<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Users;

use Kodekit\Library;
use Kodekit\Platform\Activities;

/**
 * Users Session Activity
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Users
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