<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Recursable Database Behavior
 *
 * By default requires a 'parent_id' table column. Column can be configured using the 'parent_column' config option.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Database
 */
class DatabaseBehaviorRecursable extends DatabaseBehaviorAbstract
{
    /**
     * Nodes
     *
     * @var DatabaseRowsetInterface
     */
    private $__nodes;

    /**
     * Nodes by parent
     *
     * @var array
     */
    private $__children;

    /**
     * The parent column name
     *
     * @var string
     */
    protected $_parent_column;

    /**
     * Constructor.
     *
     * @param   ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config = null)
    {
        parent::__construct($config);

        $this->_parent_column = $config->parent_column;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'parent_column' => 'parent_id'
        ));

        parent::_initialize($config);
    }

    /**
     * Check if the node has children
     *
     * @return bool True if the node has one or more children
     */
    public function hasChildren()
    {
        $result = false;

        if(isset($this->__children[$this->id])) {
            $result = (boolean) count($this->__children[$this->id]);
        }

        return $result;
    }

    /**
     * Get the children
     *
     * @return  DatabaseRowsetInterface|null
     */
    public function getChildren()
    {
        $result = null;

        if($this->hasChildren())
        {
            $parent = $this->id;

            if(!$this->__children[$parent] instanceof DatabaseRowsetInterface)
            {
                $this->__children[$parent] = $this->getTable()->createRowset(array(
                    'data' => $this->__children[$parent]
                ));
            }

            $result = $this->__children[$parent];
        }

        return $result;
    }

    /**
     * Get the nodes
     *
     * @return DatabaseRowsetInterface
     */
    public function getNodes()
    {
        return $this->__nodes;
    }

    /**
     * Get the recursive iterator
     *
     * @param integer $max_level The maximum allowed level. 0 is used for any level
     * @param integer $parent    The key of the parent to start recursing from. 0 is used for the top level
     * @return DatabaseIteratorRecursive
     * @throws \OutOfBoundsException If a parent key is specified that doesn't exist.
     */
    public function getRecursiveIterator($max_level = 0, $parent = 0)
    {
        if($parent > 0 && !$this->__nodes->find($parent)) {
            throw new \OutOfBoundsException('Parent does not exist');
        }

        //If the parent doesn't have any children create an empty rowset
        if(isset($this->__children[$parent])) {
            $config = array('data' => $this->__children[$parent]);
        } else {
            $config = array();
        }

        $iterator = new DatabaseIteratorRecursive($this->getTable()->createRowset($config), $max_level);
        return $iterator;
    }

    /**
     * Get the methods that are available for mixin based
     *
     * @param  array $exclude   A list of methods to exclude
     * @return array  An array of methods
     */
    public function getMixableMethods($exclude = array())
    {
        $exclude = array_merge($exclude, array('getNodes'));
        $methods = parent::getMixableMethods($exclude);

        return $methods;
    }

    /**
     * Check if the behavior is supported
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        $row = $this->getMixer();

        if($row instanceof DatabaseRowInterface)
        {
            if(!$row->hasProperty($this->_parent_column))  {
                return false;
            }
        }

        return true;
    }

    /**
     * Filter the rowset
     *
     * @param DatabaseContext $context
     */
    protected function _afterSelect(DatabaseContext $context)
    {
        if(!$context->query->isCountQuery())
        {
            $rowset = ObjectConfig::unbox($context->data);

            if ($rowset instanceof DatabaseRowsetInterface)
            {
                //Store the nodes
                $this->__nodes    = $rowset;
                $this->__iterator = null;
                $this->__children = array();

                foreach ($this->__nodes as $key => $row)
                {
                    //Force mixin the behavior into each row
                    $row->mixin($this);

                    if($row->isRecursable()) {
                        $parent = (int) $row->getProperty($this->_parent_column);
                    } else {
                        $parent = 0;
                    }

                    //Store the nodes by parent
                    $this->__children[$parent][$key] = $row;
                }

                //Sort the children
                ksort($this->__children);
            }
        }
    }
}