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
 * Creatable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Users
 */
class DatabaseBehaviorCreatable extends Library\DatabaseBehaviorCreatable
{
    /**
     * Get the user that created the resource
     *
     * @return Library\UserInterface|null Returns a User object or NULL if no user could be found
     */
    public function getAuthor()
    {
        $user     = null;
        $provider = $this->getObject('user.provider');

        if($this->hasProperty('created_by') && !empty($this->created_by))
        {
            if($this->_author_id && !$provider->isLoaded($this->created_by))
            {
                $data = array(
                    'id'         => $this->_author_id,
                    'email'      => $this->_author_email,
                    'name'       => $this->_author_name,
                    'authentic'  => false,
                    'enabled'    => $this->_author_enabled,
                    'expired'    => (bool) $this->_author_activation,
                    'attributes' => json_decode($this->_author_params)
                );

                $user = $provider->store($data);
            }
            else $user = $provider->load($this->created_by);
        }

        return $user;
    }

    /**
     * Set created information
     *
     * Requires a 'created_by' column
     *
     * @param Library\DatabaseContext	$context A database context object
     * @return void
     */
    protected function _beforeSelect(Library\DatabaseContext $context)
    {
        $context->query
            ->columns(array('_author_id'         => '_author.users_user_id'))
            ->columns(array('_author_name'       => '_author.name'))
            ->columns(array('_author_email'      => '_author.email'))
            ->columns(array('_author_params'     => '_author.params'))
            ->columns(array('_author_enabled'    => '_author.enabled'))
            ->columns(array('_author_activation' => '_author.activation'))
            ->columns(array('created_by_name'    => '_author.name'))
            ->join(array('_author' => 'users'), 'tbl.created_by = _author.users_user_id');
    }
}
