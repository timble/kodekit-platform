<?php

class ComFilesDatabaseTablePaths extends KDatabaseTableAbstract
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'filters' => array(
				'identifier' => 'raw',
				'path' => 'raw',
				'parameters' => 'raw'
			)
		));

		parent::_initialize($config);
	}
}
