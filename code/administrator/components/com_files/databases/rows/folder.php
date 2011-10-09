<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Folder Database Row Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesDatabaseRowFolder extends KDatabaseRowAbstract
{
	/**
	 * Nodes object or identifier (com://APP/COMPONENT.rowset.NAME)
	 *
	 * @var string|object
	 */
	protected $_children = null;

	/**
	 * Node object or identifier (com://APP/COMPONENT.rowset.NAME)
	 *
	 * @var string|object
	 */
	protected $_parent   = null;

	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->mixin(new KMixinCommandchain($config->append(array('mixer' => $this))));

		if ($config->validator !== false)
		{
			if ($config->validator === true) {
				$config->validator = 'com://admin/files.command.validator.'.$this->getIdentifier()->name;
			}

			$this->getCommandChain()->enqueue($this->getService($config->validator));
		}

		$this->registerCallback(array('after.save', 'after.delete'), array($this, 'setPath'));
	}

	public function setPath(KCommandContext $context)
	{
		if ($this->parent) {
			$this->path = $this->parent.'/'.$this->path;
			$this->parent = '';
		}
	}

	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'dispatch_events'   => false,
			'enable_callbacks'  => true,
			'validator' 		=> true
		));

		parent::_initialize($config);
	}

	public function save()
	{
		$context = $this->getCommandContext();
		$context->result = false;

		if ($this->getCommandChain()->run('before.save', $context) !== false) {

			if ($this->isNew()) {
				$context->result = mkdir($this->fullpath, 0755, true);
			}

			$this->getCommandChain()->run('after.save', $context);
		}

		if ($context->result === false) {
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage($context->getError());
		}

		return $context->result;
	}

	public function delete()
	{
		$context = $this->getCommandContext();
		$context->result = false;

		if ($this->getCommandChain()->run('before.delete', $context) !== false) {
			$context->result = !$this->isNew() ? $this->_deleteFolder($this->fullpath) : false;

			$this->getCommandChain()->run('after.delete', $context);
		}

		if ($context->result === false) {
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage($context->getError());
		}

		return $context->result;
	}

	/**
	 *
	 * Method to recursively delete a folder
	 * @param string $path
	 */
	protected function _deleteFolder($path)
	{
		if (!file_exists($path)) {
			return true; // already gone?
		}
		
		$iter = new RecursiveDirectoryIterator($path);
		foreach (new RecursiveIteratorIterator($iter, RecursiveIteratorIterator::CHILD_FIRST) as $f) {
			if ($f->isDir()) {
				rmdir($f->getPathname());
			} else {
				unlink($f->getPathname());
			}
		}

		return rmdir($path);
	}

	public function isNew()
	{
		return $this->path ? !is_dir($this->fullpath) : true;
	}

	public function toArray()
	{
		$data = parent::toArray();

		unset($data['basepath']);

		$data['type'] = 'folder';
		$data['name'] = $this->name;
		
		if ($this->hasChildren()) {
			$data['children'] = $this->getChildren()->toArray();
		}

		return $data;
	}

	public function __get($column)
	{
		if ($column == 'fullpath' && !isset($this->_data['fullpath'])) {
			return $this->getFullpath();
		}

		if ($column == 'name' && !isset($this->_data['name'])) {
			return basename($this->getFullpath());
		}

		if ($column == 'basepath' && !isset($this->_data['basepath'])) {
			$this->_data['basepath'] = $this->getBasepath();
		}

		if ($column == 'children' && !isset($this->_data['children'])) {
			$this->_data['children'] = $this->getService('com://admin/files.database.rowset.folders');
		}

		return parent::__get($column);
	}

	public function __set($column, $value)
	{
		if ($column == 'parent') {
			$this->_data['parent'] = trim($value, '\\/');
		}
		else {
			parent::__set($column, $value);
		}
	}

	public function getData($modified = false)
	{
		$result = parent::getData($modified);

		if (isset($result['children']) && $result['children'] instanceof KDatabaseRowsetInterface) {
			$result['children'] = $result['children']->getData();
		}

		return $result;
	}

	public function getFullpath()
	{
		$path = rtrim($this->basepath, '/');
		if ($this->parent) {
			$path .= '/'.$this->parent;
		}
		$path .= '/'.$this->path;

		return $path;
	}

	public function getBasepath()
	{
		return $this->basepath;
	}

	public function insertChild(KDatabaseRowInterface $node)
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
		if(!($this->_children instanceof KDatabaseRowsetInterface))
		{
			$identifier         = clone $this->getIdentifier();
			$identifier->path   = array('database', 'rowset');
			$identifier->name   = KInflector::pluralize($this->getIdentifier()->name);

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
	 * @return	object
	 */
	public function getParent()
	{
		return $this->_parent;
	}

	/**
	 * Set the parent node
	 *
	 * @return ComArticlesDatabaseRowNode
	 */
	public function setParent( $node )
	{
		$this->_parent = $node;
		return $this;
	}
}