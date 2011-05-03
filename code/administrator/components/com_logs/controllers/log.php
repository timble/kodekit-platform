<?php
/** $Id: log.php 1310 2010-09-19 14:46:25Z johanjanssens $ */

class ComLogsControllerLog extends ComDefaultControllerDefault
{
	protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'dispatch_events'	=> false
        ));

        parent::_initialize($config);
    }

	protected function _actionAdd(KCommandContext $context)
	{
		$identifier = $context->caller->getIdentifier();
				
		$data = array();
		$data['action']			= $context->data->action;
		$data['application']	= $identifier->application;
		$data['type']			= $identifier->type;
		$data['package']		= $identifier->package;
		$data['name']			= $identifier->name;

		$rowset = array();
		if ($context->result instanceof KDatabaseRowAbstract) {
			$rowset[] =  $context->result;
		} else {
			$rowset = KConfig::toData($context->result);
		}

		foreach ($rowset as $row)
		{
			//Only log if the row status is valid.
			$status = $row->getStatus();
			
			if(!empty($status))
			{
				if ($row->title) {
					$data['title'] = $row->title;
				} elseif ($row->name) {
					$data['title'] = $row->name;
				} else {
					$data['title'] = '#'.$row->id;
				}

				$data['row_id'] = $row->id;
				
				KFactory::tmp('admin::com.logs.model.logs')
					->getItem()
					->setData($data)
					->save();
			}
		}
	}
}