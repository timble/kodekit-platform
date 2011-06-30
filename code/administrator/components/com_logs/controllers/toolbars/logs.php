<?php

class ComLogsControllerToolbarLogs extends ComDefaultControllerToolbarDefault
{
    public function getCommands()
    {
        $this->reset()->addDelete();
        
        return parent::getCommands();
    }
    
    protected function _commandDelete(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs' => array(
                'data-url' => 'index.php?option=com_logs&view=logs',
                'data-action' => 'delete'
            )
        ));
    }
}