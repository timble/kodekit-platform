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
 * Users User Activity
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Users
 */
class ActivityUser extends Activities\ModelEntityActivity
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'format' => null
        ));

        parent::_initialize($config);
    }

    public function getPropertyFormat()
    {
        if (!$this->_format)
        {
            if ($this->_isEditOwn()) {
                $format = '{actor} {action} own {object.subtype} {object.type}';
            } else {
                $format = '{actor} {action} {object.type} name {object}';
            }

            $this->_format = $format;
        }

        return parent::getPropertyFormat();
    }

    protected function _objectConfig(Library\ObjectConfig $config)
    {
        if ($this->_isEditOwn())
        {
            $config->append(array(
                'type'    => array('objectName' => 'profile'),
                'subtype' => array('objectName' => 'user', 'object' => true)
            ));
        }

        parent::_objectConfig($config);
    }

    /**
     * Tells if the current activity is an own edit.
     *
     * @return bool True if it is, false otherwise.
     */
    protected function _isEditOwn()
    {
        return (bool) ($this->verb == 'edit' && $this->row == $this->created_by);
    }
}