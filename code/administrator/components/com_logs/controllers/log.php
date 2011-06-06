<?php
/** $Id$ */

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
}