<?php
/**
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Node Database Row Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Categories
 */

class ComCategoriesDatabaseRowNode extends Framework\DatabaseRowTable
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
     * @param   Config $object An optional Framework\Config object with configuration options
     * @return  void
     */
    protected function _initialize(Framework\Config $config)
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
     * @param  object $node A Framework\DatabaseRow object to be inserted
     * @return \Framework\DatabaseRowsetInterface
     */
	public function insertChild(Framework\DatabaseRowInterface $node)
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
     * @return	\Framework\DatabaseRowInterface
     */
	public function getChildren()
	{
		if(!($this->_children instanceof Framework\DatabaseRowsetInterface))
        {
            $identifier         = clone $this->getIdentifier();
            $identifier->path   = array('database', 'rowset');
            $identifier->name   = Framework\Inflector::pluralize($this->getIdentifier()->name);
            
            //The row default options
            $options  = array(
                'identity_column' => $this->getIdentityColumn()
            );
               
            $this->_children = $this->getService($identifier, $options); 
        }
        
	    return $this->_children;
	}

	/**
     * Get the parent node
     *
     * @return	\Framework\DatabaseRowInterface
     */
	public function getParent()
	{
		return $this->_parent;
	}

	/**
     * Set the parent node
     *
     * @param object $node The parent node
     * @return \ComCategoriesDatabaseRowNode
     */
	public function setParent(Framework\DatabaseRowInterface $node )
	{
		$this->_parent = $node;
		return $this;
	}
}