<?php

class ComFilesDatabaseRowNode extends KDatabaseRowAbstract
{
	protected $_adapter;
	
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

	public function isNew()
	{
		return empty($this->name) || !$this->_adapter->exists();
	}

	public function delete()
	{
		$context = $this->getCommandContext();
		$context->result = false;

		if ($this->getCommandChain()->run('before.delete', $context) !== false)
		{
			$context->result = $this->_adapter->delete();

			$this->getCommandChain()->run('after.delete', $context);
        }

		if ($context->result === false) {
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage($context->getError());
		} else {
			$this->setStatus(KDatabase::STATUS_DELETED);
		}

		return $context->result;
	}

	public function __get($column)
	{
		if ($column == 'fullpath' && !isset($this->_data['fullpath'])) {
			return $this->getFullpath();
		}
		
		if ($column == 'path') {
			return trim(($this->folder ? $this->folder.'/' : '').$this->name, '/\\');
		}
		
		if ($column == 'adapter') {
			return $this->_adapter;
		}
		

		return parent::__get($column);
	}		
	
	public function __set($column, $value)
	{
		parent::__set($column, $value);
		
		if (in_array($column, array('container', 'folder', 'name'))) {
			$this->setAdapter();
		}
	}	
	
	public function setAdapter()
	{
		$type = $this->getIdentifier()->name;
		$this->_adapter = $this->container->getAdapter($type, array(
			'path' => $this->container->path.'/'.($this->folder ? $this->folder.'/' : '').$this->name
		));
		
		unset($this->_data['fullpath']);
		unset($this->_data['metadata']);
		
		return $this;
	}
	
	public function setData($data, $modified = true)
	{
		$result = parent::setData($data, $modified);
		
		if (isset($data['container'])) {
			$this->setAdapter();
		}
		
		return $result;
	}

	public function getFullpath()
	{
		return $this->_adapter->getRealPath();
	}

    public function toArray()
    {
        $data = parent::toArray();
        
        unset($data['_token']);
        unset($data['action']);
        unset($data['option']);
        unset($data['format']);
        unset($data['view']);
        
		$data['container'] = $this->container->slug;
		$data['type'] = $this->getIdentifier()->name;

        return $data;
    }	
}
