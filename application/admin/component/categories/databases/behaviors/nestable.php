<?php
/**
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Nestable Database Behavior Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Categories
 */

class ComCategoriesDatabaseBehaviorNestable extends Framework\DatabaseBehaviorAbstract
{
    protected $_table;

    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        foreach($config as $key => $value) {
            $this->{'_'.$key} = $value;
        }
    }

    protected function _initialize(Framework\Config $config)
    {
        $config->append(
            array('table' => null)
        );

        parent::_initialize($config);
    }

    protected function _beforeTableSelect(Framework\CommandContext $context)
    {
        if($context->query instanceof Framework\DatabaseQuerySelect && $context->mode == Framework\Database::FETCH_ROWSET)
        {
            $this->_table = $context->getSubject();

            $this->_table->getAdapter()
                         ->getCommandChain()
                         ->enqueue($this, $this->getPriority());
        }
    }

    protected function _afterTableSelect(Framework\CommandContext $context)
    {
        if(isset($this->_table))
        {
            $this->_table->getAdapter()
                        ->getCommandChain()
                        ->dequeue($this);

            $this->_table = null;
        }
    }

    protected function _beforeAdapterSelect(Framework\CommandContext $context)
    {
        $context->limit  = $context->query->limit;
        $context->offset = $context->query->offset;

        $context->query->limit(0);
    }

    protected function _afterAdapterSelect(Framework\CommandContext $context)
    {
        //Get the data
        $rows = Framework\Config::unbox($context->result);

        if(is_array($rows))
        {
            $children = array();
            $result = array();

            /*
            * Create the children array
            */
            foreach($rows as $key => $row)
            {
                $path   = array();
                $parent = $row['parent_id'];

                //Store node by parent
                if(!empty($parent) && isset($rows[$parent])) {
                    $children[$parent][] = $key;
                }
            }

            /*
             * Create the result array
             */
            foreach($rows as $key => $row)
            {
                if(empty($row['parent_id']))
                {
                    $result[$key] = $row;

                    if(isset($children[$key])) {
                        $this->_getChildren($rows, $children, $key, $result);
                    }
                }
            }

            /*
             * If we have not been able to match all children to their parents don't perform
             * the path enumeration for the children.
             */
            if(count($result) == count($rows))
            {
                if($context->limit) {
                    $result = array_slice( $result, $context->offset, $context->limit, true);
                }

                /*
                  * Create the paths of each node
                  */
                foreach($result as $key => $row)
                {
                    $path   = array();
                    $parent = $row['parent_id'];

                    if(!empty($parent))
                    {
                        $table  = $this->_table;

                        //Create node path
                        $path = $result[$parent]['path'];
                        $id   = $result[$parent][$table->getIdentityColumn()];

                        $path[] = $id;
                    }

                    //Set the node path
                    $result[$key]['path'] = $path;
                }
            }
            else $result = $rows;

            $context->result = $result;
        }
    }

    protected function _getChildren($rows, $children, $parent, &$result)
    {
        foreach($children[$parent] as $child)
        {
            //Add the child to the rows
            $result[$child] = $rows[$child];

            if(isset($children[$child])) {
                $this->_getChildren($rows, $children, $child, $result);
            }
        }
    }
}