<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Pages Database Table Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseTablePages extends Framework\DatabaseTableDefault
{
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'name' => 'pages',
            'behaviors'  => array(
                'creatable', /*'modifiable',*/ 'lockable', 'sluggable', 'assignable', 'typable',
                'com://admin/pages.database.behavior.orderable' => array(
                    'strategy' => 'closure',
                    'table'    => 'com://admin/pages.database.table.orderings',
                    'columns'  => array('title', 'custom')
                ),
                'com://admin/pages.database.behavior.closurable' => array(
                    'table' => 'com://admin/pages.database.table.closures'
                )
            ),
            'filters' => array(
                'params' => 'ini'
            )
        ));

        parent::_initialize($config);
    }
}
