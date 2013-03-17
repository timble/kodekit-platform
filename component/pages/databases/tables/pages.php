<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Pages;

use Nooku\Framework;

/**
 * Pages Database Table
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Pages
 */
class DatabaseTablePages extends Framework\DatabaseTableDefault
{
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'name' => 'pages',
            'behaviors'  => array(
                'creatable', /*'modifiable',*/ 'lockable', 'sluggable', 'assignable', 'typable',
                'com:pages.database.behavior.orderable' => array(
                    'strategy' => 'closure',
                    'table'    => 'com:pages.database.table.orderings',
                    'columns'  => array('title', 'custom')
                ),
                'com:pages.database.behavior.closurable' => array(
                    'table' => 'com:pages.database.table.closures'
                )
            ),
            'filters' => array(
                'params' => 'ini'
            )
        ));

        parent::_initialize($config);
    }
}
