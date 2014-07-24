<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Modifiable Database Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Users
 */
class DatabaseBehaviorModifiable extends Library\DatabaseBehaviorModifiable
{
    /**
     * Get the user that last edited the resource
     *
     * @return Library\UserInterface|null Returns a User object or NULL if no user could be found
     */
    public function getEditor()
    {
        $user     = null;
        $provider = $this->getObject('user.provider');

        if($this->hasProperty('modified_by') && !empty($this->modified_by))
        {
            if($this->_editor_id && !$provider->isLoaded($this->modified_by))
            {
                $data = array(
                    'id'         => $this->_editor_id,
                    'email'      => $this->_editor_email,
                    'name'       => $this->_editor_name,
                    'authentic'  => false,
                    'enabled'    => !$this->_editor_block,
                    'expired'    => (bool) $this->_editor_activation,
                    'attributes' => json_decode($this->_editor_params)
                );

                $user = $provider->store($data);
            }
            else $user = $provider->load($this->modified_by);
        }

        return $user;
    }

    /**
     * Set created information
     *
     * Requires a 'modified_by' column
     *
     * @param Library\DatabaseContext	$context A database context object
     * @return void
     */
    protected function _beforeSelect(Library\DatabaseContext $context)
    {
        $context->query
            ->columns(array('_editor_id'         => '_editor.users_user_id'))
            ->columns(array('_editor_name'       => '_editor.name'))
            ->columns(array('_editor_email'      => '_editor.email'))
            ->columns(array('_editor_params'     => '_editor.params'))
            ->columns(array('_editor_enabled'    => '_editor.enabled'))
            ->columns(array('_editor_activation' => '_editor.activation'))
            ->columns(array('modified_by_name'   => '_editor.name'))
            ->join(array('_editor' => 'users'), 'tbl.modified_by = _editor.users_user_id');
    }
}

