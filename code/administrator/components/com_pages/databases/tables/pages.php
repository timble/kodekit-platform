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

class ComPagesDatabaseTablePages extends ComPagesDatabaseTableClosures
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        if(empty($config->ordering_table)) {
            throw new KDatabaseTableException('Ordering table cannot be empty');
        }

        $this->setOrderingTable($config->ordering_table);
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'name' => 'pages',
            'relation_table' => 'pages_closures',
            'ordering_table' => 'pages_orderings',
            'behaviors'  => array(
                'creatable', 'modifiable', 'lockable', 'sluggable', 'assignable',
                'com://admin/pages.database.behavior.orderable' => array('columns' => array('title', 'custom'))
            ),
            'filters' => array(
                'params' => 'ini'
            )
        ));

        parent::_initialize($config);
    }

    public function getOrderingTable()
    {
        return $this->_ordering_table;
    }

    public function setOrderingTable($table)
    {
        $this->_ordering_table = $table;
        return $this;
    }
}
