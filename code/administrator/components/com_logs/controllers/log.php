<?php
/** $Id$ */

class ComLogsControllerLog extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $this->_request->package = $config->package;
        
        if ($this->isDispatched() && $config->package) {
            $this->_request->layout = 'package_list';
            
            // Inherit the views from the calling component's view
            $view = clone $this->getView()->getIdentifier();
            $view->package = $config->package;
            
            $this->getView()->views = KFactory::get($view)->views;
        }
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'package' => null,
        ));
        
        parent::_initialize($config);
        
        $config->view = 'admin::com.logs.view.logs.html';
        $config->model = 'admin::com.logs.model.logs';
        $config->toolbar = 'admin::com.logs.controller.toolbar.logs';
    }
    
    public function loadState(KCommandContext $context)
	{
		// Built the session identifier based on the action
		$identifier  = $this->getModel()->getIdentifier().'.'.$context->action;
		$state       = KRequest::get('session.'.$identifier, 'raw', array());
		$state['id'] = null;
        
		//Append the data to the request object
		$this->_request->append($state);
		
		//Push the request in the model
		$this->getModel()->set($this->getRequest());
		
		return $this;
	}
}