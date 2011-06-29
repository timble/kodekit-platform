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

jimport('joomla.filesystem.folder');

class ComFilesDatabaseRowFolder extends KDatabaseRowAbstract
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->mixin(new KMixinCommandchain($config->append(array('mixer' => $this))));

		if ($config->validator !== false)
		{
        	if ($config->validator === true) {
        		$config->validator = 'admin::com.files.command.validator.'.$this->getIdentifier()->name;
        	}

			$this->getCommandChain()->enqueue(KFactory::tmp($config->validator));
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
				$context->result = JFolder::create($this->fullpath);
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
        	$context->result = !$this->isNew() ? JFolder::delete($this->fullpath) : false;

			$this->getCommandChain()->run('after.delete', $context);
        }

		if ($context->result === false) {
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage($context->getError());
		}

		return $context->result;
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
		$data['name'] = 'folder';

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
			$this->_data['children'] = KFactory::tmp('admin::com.files.database.rowset.folders');
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
}