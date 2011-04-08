<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Users Model Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersModelUsers extends KModelTable
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_state
            ->insert('email'       , 'email', null, true)
            ->insert('group_name'  , 'string')
            ->insert('username'    , 'alnum', null, true);
    }

    public function getParameters()
    {
        return KFactory::get('lib.joomla.application')->getParams();
    }

    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
        parent::_buildQueryWhere($query);

        if($this->_state->group_name) {
            // @TODO: Change usertype to group_name when mapping is fixed.
            $query->where('LOWER(usertype)', '=', $this->_state->group_name);
        }
    }
}