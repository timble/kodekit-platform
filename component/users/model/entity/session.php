<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-users for the canonical source repository
 */

namespace Kodekit\Component\Users;

use Kodekit\Library;

/**
 * Session Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Users
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

        if(!empty($this->id))
        {
            if($result = parent::save())
            {
                $user = $this->getObject('user.provider')->getUser($this->email);

                // Hit the user last visit field
                if($user->getId())
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