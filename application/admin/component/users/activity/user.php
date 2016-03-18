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
 * Users User Activity
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Users
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