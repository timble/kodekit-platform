<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Database Recursive Iterator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Database
 */
class DatabaseIteratorRecursive extends \RecursiveIteratorIterator
{
    /**
     * Constructor
     *
     * @param DatabaseRowsetInterface $rowset
     * @param integer $max_level The maximum allowed level. 0 is used for any level
     * @return DatabaseIteratorRecursive
     */
    public function __construct(DatabaseRowsetInterface $rowset, $max_level = 0)
    {
        parent::__construct(static::_createInnerIterator($rowset), \RecursiveIteratorIterator::SELF_FIRST);

        //Set the max iteration level
        if(isset($max_level)) {
            $this->setMaxLevel($max_level);
        }
    }

    /**
     * Get children of the current node
     *
     * @return \RecursiveIterator
     */
    public function callGetChildren()
    {
        return static::_createInnerIterator($this->current()->getChildren());
    }

    /*
     * Called for each element to test whether it has children.
     *
     * @return bool TRUE if the element has children, otherwise FALSE
     */
    public function callHasChildren()
    {
        $result = false;

        if($this->current()->isRecursable()) {
            $result = $this->current()->hasChildren();
        }

        return $result;
    }

    /**
     * Set the maximum iterator level
     *
     * @param int $max
     * @return DatabaseIteratorRecursive
     */
    public function setMaxLevel($max = 0)
    {
        //Set the max depth for the iterator
        $this->setMaxDepth((int) $max - 1);
        return $this;
    }

    /**
     * Get the current iteration level
     *
     * @return int
     */
    public function getLevel()
    {
        return (int) $this->getDepth() + 1;
    }

    /**
     * Create a recursive iterator from a rowset
     *
     * @param DatabaseRowsetInterface $rowset
     * @return \RecursiveIterator
     */
    protected static function _createInnerIterator(DatabaseRowsetInterface $rowset)
    {
        $iterator = new \RecursiveArrayIterator($rowset->getIterator());
        $iterator = new \RecursiveCachingIterator($iterator, \CachingIterator::TOSTRING_USE_KEY);

        return $iterator;
    }
}