<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Node Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Categories
 */
class ModelEntityNode extends Library\ModelEntityRow
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
 	protected $_parent   = null;

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Library\ObjectConfig $object An optional Library\ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'children'  => null,
            'parent'	=> null,
        ));

        parent::_initialize($config);
    }
    
    /**
     * Insert a child row
     *
     * The row will be stored by it's identity_column if set or otherwise by it's object handle.
     *
     * @param  Library\ModelEntityInterface $node The node to be inserted
     * @return ModelEntityNode
     */
	public function insertChild(Library\ModelEntityInterface $node)
 	{
 		//Track the parent
 		$node->setParent($this);

 		//Insert the row in the rowset
 		$this->getChildren()->insert($node);
 		
 		return $this;
 	}

    /**
     * Check if the node has children
     *
     * @return bool True if the node has one or more children
     */
    public function hasChildren()
	{
        return (boolean) count($this->_children);
	}

	/**
     * Get the children rowset
     *
     * @return	Library\ModelEntityInterface
     */
	public function getChildren()
	{
        if(!($this->_children instanceof Library\ModelEntityInterface))
        {
            $identifier         = $this->getIdentifier()->toArray();
            $identifier['path'] = array('model', 'entity');
            $identifier['name'] = Library\StringInflector::pluralize($this->getIdentifier()->name);
            
            //The row default options
            $options  = array(
                'identity_column' => $this->getIdentityKey()
            );
               
            $this->_children = $this->getObject($identifier, $options);
        }

	    return $this->_children;
	}

	/**
     * Get the parent node
     *
     * @return	Library\ModelEntityInterface
     */
	public function getParent()
	{
		return $this->_parent;
	}

	/**
     * Set the parent node
     *
     * @param Library\ModelEntityInterface $node The parent node
     * @return ModelEntityNode
     */
	public function setParent(Library\ModelEntityInterface $node )
	{
		$this->_parent = $node;
		return $this;
	}
}