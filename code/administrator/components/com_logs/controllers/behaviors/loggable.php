<?php

class ComLogsControllerBehaviorLoggable extends KControllerBehaviorAbstract
{
    public function __construct(KConfig $config)
    { 
        parent::__construct($config);

        $this->registerCallback($actions, array($this, 'logAction'));
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'priority'   => KCommand::PRIORITY_LOW,
            'actions' => array('after.edit', 'after.add', 'after.delete'),
        ));

        parent::_initialize($config);
    }
    
    public function logAction(KCommandContext $context)
    {
        $identifier = $context->caller->getIdentifier();
				
		$data = array();
		$data['action']			= $context->action;
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