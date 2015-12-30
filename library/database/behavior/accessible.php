<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Permissible Database Behavior
 *
 * - Requires an 'access' table column to define if the row is accessible by everyone or only be registered user.
 * - Requires an 'access_group' table column to define which user group(s) have access.
 * - Requires an 'access_role' table column to define which user role(s) have access.
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Nooku\Library\Database
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