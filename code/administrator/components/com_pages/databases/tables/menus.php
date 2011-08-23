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
 * Menus Database Table Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseTableMenus extends KDatabaseTableDefault
{
	protected function _initialize(KConfig $config)
    {
        $config->identity_column = 'id';    	
        
        $config->append(array(
			'name'			=> 'menu_types',
			'column_map'	=> array(
				'name'	=> 'menutype'
			)
		));

		parent::_initialize($config);
    }
}