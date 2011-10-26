<?php

class ComActivitiesDispatcher extends ComDefaultDispatcher
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'request' => array(
				'view' => 'activities'
			),
		));
	
		parent::_initialize($config);
	}
}