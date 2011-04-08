<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Listbox Template Helper Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class ComUsersTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    public function groups($config = array())
    {
        $config = new KConfig($config);
        $config->append(array('selected' => 0));

        $acl    = KFactory::get('lib.joomla.acl');
        $tree   = $acl->get_group_children_tree(null, 'USERS', false);

        return JHTML::_('select.genericlist', $tree, 'users_group_id', 'size="10"', 'value', 'text', $config->selected);
    }
}