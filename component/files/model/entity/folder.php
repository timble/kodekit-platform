<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Folder Model Entity
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ModelEntityFolder extends ModelEntityNode
{
    /**
     * Nodes object or identifier
     *
     * @var string|object
     */
    protected $_children = null;

    /**
     * Node object or identifier
     *
     * @var string|object
     */
    protected $_parent = null;

    public function save()
    {
        $context         = $this->getContext();
        $context->result = false;

        $is_new = $this->isNew();

        if ($this->invokeCommand('before.save', $context, false) !== false)
        {
            if ($this->isNew()) {
                $context->result = $this->_adapter->create();
            }

            $this->invokeCommand('after.save', $context);
        }

        if ($context->result === false) {
            $this->setStatus(self::STATUS_FAILED);
        } else {
            $this->setStatus($is_new ? self::STATUS_CREATED : self::STATUS_UPDATED);
        }

        return $context->result;
    }

    public function getPropertyChildren()
    {
        return $this->getObject('com:files.model.entity.folders');
    }

    public function toArray()
    {
        $data = parent::toArray();

        if ($this->hasChildren()) {
            $data['children'] = $this->getChildren()->toArray();
        }

        return $data;
    }

    public function getProperties($modified = false)
    {
        $result = parent::getProperties($modified);

        if (isset($result['children']) && $result['children'] instanceof Library\ModelEntityInterface) {
            $result['children'] = $result['children']->getProperties();
        }

        return $result;
    }

    public function insertChild(Library\ModelEntityInterface $node)
    {
        //Track the parent
        $node->setParent($this);

        //Insert the row in the rowset
        $this->getChildren()->insert($node);

        return $this;
    }

    public function hasChildren()
    {
        return (boolean)count($this->_children);
    }

    /**
     * Get the children rowset
     *
     * @return    object
     */
    public function getChildren()
    {
        if (!($this->_children instanceof Library\ModelEntityInterface))
        {
            $identifier         = $this->getIdentifier()->toArray();
            $identifier['path'] = array('model', 'entity');
            $identifier['name'] = Library\StringInflector::pluralize($this->getIdentifier()->name);

            //The row default options
            $options = array(
                'identity_key' => $this->getIdentityKey()
            );

            $this->_children = $this->getObject($identifier, $options);
        }

        return $this->_children;
    }

    /**
     * Get the parent node
     *
     * @return    object
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * Set the parent nodeD
     *
     * @return ModelEntityFolder
     */
    public function setParent($node)
    {
        $this->_parent = $node;
        return $this;
    }
}