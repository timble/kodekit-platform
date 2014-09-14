<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Lockable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Users
 */
class DatabaseBehaviorLockable extends Library\DatabaseBehaviorLockable
{
    /**
     * Get the user that owns the lock on the resource
     *
     * @return Library\UserInterface|null Returns a User object or NULL if no user could be found
     */
    public function getLocker()
    {
        $user     = null;
        $provider = $this->getObject('user.provider');

        if($this->hasProperty('locked_by') && !empty($this->locked_by))
        {
            if($this->_owner_id && !$provider->isLoaded($this->locked_by))
            {
                $data = array(
                    'id'         => $this->_owner_id,
                    'email'      => $this->_owner_email,
                    'name'       => $this->_owner_name,
                    'authentic'  => false,
                    'enabled'    => $this->_owner_enabled,
                    'expired'    => (bool) $this->_owner_activation,
                    'attributes' => json_decode($this->_owner_params)
                );

                $user = $this->getObject('user.provider')->store($data);
            }
            else $user = $this->getObject('user.provider')->load($this->locked_by);
        }

        return $user;
    }

    /**
     * Set created information
     *
     * Requires a 'locked_by' column
     *
     * @param Library\DatabaseContext	$context A database context object
     * @return void
     */
    protected function _beforeSelect(Library\DatabaseContext $context)
    {
        $context->query
            ->columns(array('_owner_id'         => '_owner.users_user_id'))
            ->columns(array('_owner_name'       => '_owner.name'))
            ->columns(array('_owner_email'      => '_owner.email'))
            ->columns(array('_owner_params'     => '_owner.params'))
            ->columns(array('_owner_enabled'    => '_owner.enabled'))
            ->columns(array('_owner_activation' => '_owner.activation'))
            ->columns(array('locked_by_name'    => '_owner.name'))
            ->join(array('_owner' => 'users'), 'tbl.locked_by = _owner.users_user_id');
    }
}

