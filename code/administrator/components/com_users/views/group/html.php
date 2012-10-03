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
 * Group HTML view class.
 *
 * @author     Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersViewGroupHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $group = $this->getModel()->getItem();
        $users = array();
        
        if($group->id){
        	$groups_users = $this->getService('com://admin/users.model.groups_users')->users_group_id($group->id)->getList();
        	
        	foreach ($groups_users as $key => $value) {
        		$users[] = $value->users_user_id;
        	}
        }
        
        $this->assign('users', $users);
        
        return parent::display();
    }
}