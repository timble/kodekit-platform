<?php
class ComGroupsDatabaseTableGroups extends ComGroupsDatabaseTableNodes
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
            'name'  => 'core_acl_aro_groups',
            'base'  => 'core_acl_aro_groups'
        ));

        parent::_initialize($config);
	}
}