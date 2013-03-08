<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Session Database Row Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersDatabaseRowSession extends Framework\DatabaseRowTable
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
                $row = $this->getService('com://admin/users.database.row.user')
                            ->setData(array('email' => $this->email))
                            ->load();

                if($row)
                {
                    $row->last_visited_on = gmdate('Y-m-d H:i:s');
                    $row->save();

                    $this->setStatus(self::LOGGED_IN);
                }
            }
        }

        return $result;
    }
}