<?php

class ComFilesModelDefault extends KModelAbstract
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state
			->insert('limit'    , 'int', 0)
			->insert('offset'   , 'int', 0)
			->insert('search'	, 'filename')
			->insert('direction', 'word', 'asc')

			->insert('identifier', 'identifier', null)
			->insert('path'		, 'admin::com.files.filter.path', null, true) // unique
			->insert('folder'	, 'admin::com.files.filter.path', '')
			->insert('type'		, 'cmd', '')
			;
	}

	protected function _initialize(KConfig $config)
	{
		$config->append(array(
            'state'      => KFactory::tmp('admin::com.files.model.state.node'),
       	));

       	parent::_initialize($config);
    }
}