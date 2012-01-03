<?php

class ComFilesConfigState extends KConfigState
{
	public function get($name, $default = null)
    {
    	$result = parent::get($name, $default);
    	
        if ($name === 'container' && is_string($result)) {
            $result = KService::get('com://admin/files.model.containers')->slug($result)->getItem();

	        if (!is_object($result) || $result->isNew()) {
	            throw new KModelException('Invalid container');
	        }
	        
	        $this->_data['container']->value = $result;
        }
        
        return $result;
  	}
}