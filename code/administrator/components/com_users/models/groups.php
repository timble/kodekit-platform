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
class ComUsersModelGroups extends KModelTable
{
    protected $_tree;

    public function getTree()
    {
        if(!isset($this->_tree))
        {
	        $this->_tree   = KFactory::get('lib.joomla.acl')->get_group_children_tree(null, 'USERS', false);
	        return $this->_tree;

	        /* @TODO: Fix query when query refactoring branch is merged.

            $table = $this->getTable();

            $query = $table->getDatabase()->getQuery()
                ->select(array('node.id', 'node.name', '(COUNT(parent.name) - 3) AS depth'))
                ->from('core_acl_aro_groups AS node')
                ->from('core_acl_aro_groups AS parent')
                ->where('parent.lft', '<', 'node.lft')
                ->where('node.lft', '<', 'parent.rgt')
                ->where('node.name', '<>', 'ROOT')
                ->where('node.name', '<>', 'USERS')
                ->group('node.id')
                ->order('node.lft');

            $this->_tree = $table->select($query, KDatabase::FETCH_ROWSET);
            */
        }

        return $this->_tree;
    }
}