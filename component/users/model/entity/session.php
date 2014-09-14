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
 * Session Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Users
 */
class ModelEntitySession extends Library\ModelEntityRow
{
    const LOGGED_IN  = 'logged in';
    const LOGGED_OUT = 'logged out';

    public function delete()
    {
        if($result = parent::delete()) {
            $this->setStatus(self::LOGGED_OUT);
        }

        return $result;
    }

    public function save()
    {
        $result = false;

        //@TODO : Implement automatic schema validation
        if(!empty($this->id))
        {
            if($result = parent::save())
            {
                // Hit the user last visit field
                $user = $this->getObject('com:users.model.users')
                            ->email($this->email)
                            ->fetch();

                if($user->id)
                {
                    $user->last_visited_on = gmdate('Y-m-d H:i:s');
                    $user->save();

                    $this->setStatus(self::LOGGED_IN);
                }
            }
        }

        return $result;
    }
}