<?php

class ComLogsControllerToolbarLogs extends ComDefaultControllerToolbarDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

		$this->addDelete();
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'auto_defaults' => false
        ));
        
        parent::_initialize($config);
    }
    
    protected function _commandDelete(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs' => array(
                'data-url' => 'index.php?option=com_logs&view=logs',
            )
        ));
    }
}