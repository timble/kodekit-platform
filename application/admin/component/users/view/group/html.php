<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Group HTML view class.
 *
 * @author     Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Users
 */
class UsersViewGroupHtml extends Library\ViewHtml
{
    public function render()
    {
        $group = $this->getModel()->getRow();

        $this->users = $this->getObject('com:users.model.groups_users')->group_id($group->id)->getRowset()->user_id;
        
        return parent::render();
    }
}