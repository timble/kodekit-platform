<?php
/** $Id$ */

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
		if(!$this->_model instanceof KModelAbstract) {
		    $this->_model = KFactory::tmp('admin::com.logs.model.logs')->set($this->_request);
		}

		return $this->_model;
	}
}