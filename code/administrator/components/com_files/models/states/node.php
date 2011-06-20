<?php

class ComFilesModelStateNode extends KModelState
{
    public function __get($name)
    {
    	if ($name == 'id') {
    		$name = 'path';
    	}

    	if ($name == 'identifier' && isset($this->_state[$name]) && is_string($this->_state[$name]->value)) {
			return $this->getIdentifier();
    	}
    	else if ($name == 'basepath') {
    		return (string) (isset($this->_state['identifier']) ? $this->getIdentifier() : '');
    	}

    	return parent::__get($name);
    }

    public function getIdentifier()
    {
    	if (is_string($this->_state['identifier']->value)) {
    		$this->_state['identifier']->value = KFactory::tmp('admin::com.files.model.paths')->identifier($this->_state['identifier']->value)->getItem();
    	}
		return $this->_state['identifier']->value;
    }
}