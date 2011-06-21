<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Node State Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

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