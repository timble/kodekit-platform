<?php
/** $Id$ */

class ComLogsControllerLog extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $this->_request->package = $config->package;
        $this->_request->layout = 'package_list';
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
    
    public function getToolbar()
    {
        $toolbar = parent::getToolbar();
        
        if ($this->_request->package) {
            $toolbar->setTitle(ucfirst($this->_request->package).' Logs');
        }
        
        return $toolbar;
    }
}