<?php
/** $Id: log.php 1310 2010-09-19 14:46:25Z johanjanssens $ */

class ComLogsControllerLog extends ComDefaultControllerDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'request' => KRequest::get('get', 'string'),
        ));
        
        parent::_initialize($config);
    }

	public function getView()
    {
        if (!$this->isDispatched()) {
            $this->_request->layout = 'package_list';
        }

        return parent::getView();
    }
    
    public function getModel()
	{
		if(!$this->_model instanceof KModelAbstract) 
		{   
		    //@TODO : Pass the state to the model using the options
		    $options = array(
				'state' => $this->_request
            );
		    
		    $this->_model = KFactory::tmp('admin::com.logs.model.logs')->set($this->_request);
		}

		return $this->_model;
	}
}