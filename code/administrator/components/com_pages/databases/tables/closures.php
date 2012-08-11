<?php
/**
 * @version     $Id: pages.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Closure Database Table Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseTableClosures extends KDatabaseTableDefault
{
    protected $_relation_table;

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        if(empty($config->relation_table)) {
            throw new KDatabaseTableException('Relation table cannot be empty');
        }

        $this->setRelationTable($config->relation_table);
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'behaviors' => array('com://admin/pages.database.behavior.closurable')
        ));

        parent::_initialize($config);
    }

    public function getRelationTable()
    {
        return $this->_relation_table;
    }

    public function setRelationTable($table)
    {
        $this->_relation_table = $table;
        return $this;
    }
}
