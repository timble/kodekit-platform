<?php
/**
 * @version     $Id: pages.php 3216 2011-11-28 15:33:44Z kotuha $
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
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseTablePages extends KDatabaseTableDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'name' => 'pages',
            'behaviors'  => array(
                'creatable', 'modifiable', 'lockable', 'sluggable', 'assignable',
                /*'com://admin/pages.database.behavior.orderable' => array(
                    'table' => 'pages_orderings',
                    'columns' => array('title', 'custom')
                ),*/
                'com://admin/pages.database.behavior.closurable' => array(
                    'table' => 'pages_closures'
                )
            ),
            'filters' => array(
                'params' => 'ini'
            )
        ));

        parent::_initialize($config);
    }
}
