 <?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Pages Database Table Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseTablePages extends KDatabaseTableDefault
{
	protected function _initialize(KConfig $config)
    {
        $config->identity_column = 'id';
        
        $config->append(array(
            'name'		 => 'menu',
			'behaviors'  => array('lockable', 'sluggable', 'nestable'),
			'column_map' => array(
				'title'			  => 'name',
				'enabled'		  => 'published',
				'locked_by'		  => 'checked_out',
				'locked_on'		  => 'checked_out_time',
				'slug'			  => 'alias',
				'group_id'		  => 'access',
				'level'			  => 'sublevel',
				'component_id'	  => 'componentid',
				'pages_menu_name' => 'menutype',
				'parent_id'		  => 'parent'
			),
			'filters' => array(
                'params' => 'ini'
		    )
		));

		parent::_initialize($config);
    }
}