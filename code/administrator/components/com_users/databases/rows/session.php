<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Session Database Row Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersDatabaseRowSession extends KDatabaseRowDefault
{
    public function delete()
    {
        if($result = parent::delete()) {
            $this->setStatus('logged out');
        }

        return $result;
    }

    public function save()
    {
        if($result = parent::save())
        {
            // Hit the user last visit field
            $row = KService::get('com://admin/users.database.row.user')
                        ->setData(array('id' => $this->user_id))
                        ->load();

            $row->last_visited_on = gmdate('Y-m-d H:i:s');
            $row->save();

            $this->setStatus('logged in');
        }

        return $result;
    }
}