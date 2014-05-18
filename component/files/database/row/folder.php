<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Folder Database Row
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class DatabaseRowFolder extends DatabaseRowNode
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

	public function save()
	{
		$context = $this->getCommandContext();
		$context->result = false;

		$is_new = $this->isNew();

		if ($this->getCommandChain()->run('before.save', $context) !== false)
		{
			if ($this->isNew()) {
				$context->result = $this->_adapter->create();
			}

			$this->getCommandChain()->run('after.save', $context);
		}

		if ($context->result === false) {
			$this->setStatus(Library\Database::STATUS_FAILED);
		} else {
            $this->setStatus($is_new ? Library\Database::STATUS_CREATED : Library\Database::STATUS_UPDATED);
        }

		return $context->result;
	}

	public function __get($column)
	{
		if ($column == 'children' && !isset($this->_data['children'])) {
			$this->_data['children'] = $this->getObject('com:files.database.rowset.folders');
		}

		return parent::__get($column);
	}

	public function toArray()
	{
		$data = parent::toArray();

		if ($this->hasChildren()) {
			$data['children'] = $this->getChildren()->toArray();
		}

		return $data;
	}

	public function getData($modified = false)
	{
		$result = parent::getData($modified);

		if (isset($result['children']) && $result['children'] instanceof Library\DatabaseRowsetInterface) {
			$result['children'] = $result['children']->getData();
		}

		return $result;
	}

	public function insertChild(Library\DatabaseRowInterface $node)
	{
		//Track the parent
		$node->setParent($this);

		//Insert the row in the rowset
		$this->getChildren()->insert($node);

		return $this;
	}

	public function hasChildren()
	{
		return (boolean) count($this->_children);
	}

	/**
	 * Get the children rowset
	 *
	 * @return	object
	 */
	public function getChildren()
	{
		if(!($this->_children instanceof Library\DatabaseRowsetInterface))
		{
			$identifier         = clone $this->getIdentifier();
			$identifier->path   = array('database', 'rowset');
			$identifier->name   = Library\StringInflector::pluralize($this->getIdentifier()->name);

			//The row default options
			$options  = array(
				'identity_column' => $this->getIdentityColumn()
			);

			$this->_children = $this->getObject($identifier, $options);
		}

		return $this->_children;
	}

	/**
	 * Get the parent node
	 *
	 * @return	object
	 */
	public function getParent()
	{
		return $this->_parent;
	}

	/**
	 * Set the parent node
	 *
	 * @return DatabaseRowFolder
	 */
	public function setParent( $node )
	{
		$this->_parent = $node;
		return $this;
	}
}