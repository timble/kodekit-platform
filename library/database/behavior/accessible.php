<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Permissible Database Behavior
 *
 * - Requires an 'access' table column to define if the row is accessible by everyone or only be registered user.
 * - Requires an 'access_group' table column to define which user group(s) have access.
 * - Requires an 'access_role' table column to define which user role(s) have access.
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Kodekit\Library\Database
 */
class DatabaseBehaviorAccessible extends DatabaseBehaviorAbstract
{
    /**
     * Check if the row can be accessed
     *
     * @return  boolean  True on success, false otherwise
     */
    public function canAccess()
    {
        //Check if the user needs to be authentic to access
        if($this->hasProperty('access') && !empty($this->access))
        {
            if(!$this->getObject('user')->isAuthentic()) {
                return false;
            }
        }

        //Check if the user is in the group(s) to access
        if($this->hasProperty('access_group') && !empty($this->access_group))
        {
            $groups = $this->getObject('user')->getGroups();

            if(!in_array($this->access_group, $groups)) {
                return false;
            }
        }

        //Check if the user has the right role(s) to access
        if($this->hasProperty('access_role') && !empty($this->access_role))
        {
            if(!$this->getObject('user')->hasRole($this->access_role)) {
                return false;
            }
        }

        return true;
    }
}