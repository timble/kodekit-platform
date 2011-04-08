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
 * Sessions Model Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersModelSessions extends KModelTable
{
    /**
     * Constructor.
     *
     * @param   KConfig  An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_state->insert('username', 'alnum');
    }

    /**
     * Builds a WHERE clause for the query.
     *
     * @param   KDatabaseQuery  A query object.
     * @return  void
     */
    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
        parent::_buildQueryWhere($query);

        if($this->_state->username) {
            $query->where('username', 'IN', $this->_state->username);
        }
    }
}