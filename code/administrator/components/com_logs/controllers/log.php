<?php
/** $Id:$ */

class ComLogsControllerLog extends ComDefaultControllerDefault
{
    protected function _initialize(KConfig $config)
    {
        // Force controller to get the GET request whether it's dispatched or not
        $config->append(array(
            'request' => KRequest::get('get', 'string'),
        ));
        
        parent::_initialize($config);
    }

	public function getView()
    {
        // If com_logs is called through HMVC, use a special layout 
        // to display the log list that is specific to the "package(component)".
        if (!$this->isDispatched()) {
            $this->_request->layout = 'package_list';
        }

        return parent::getView();
    }
}