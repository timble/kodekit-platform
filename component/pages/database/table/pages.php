<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Pages Database Table
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
 */
class DatabaseTablePages extends Library\DatabaseTableAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'name' => 'pages',
            'behaviors'  => array(
                'creatable', 'modifiable', 'lockable', 'sluggable', 'assignable', 'identifiable', 'recursable', 'accessible',
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
                'params' => 'json'
            ),
            'column_map' => array(
                'parameters' => 'params',
            )
        ));

        parent::_initialize($config);
    }
}
