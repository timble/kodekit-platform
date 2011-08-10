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

class ComFilesModelStateNode extends KConfigState
{
    public function __get($name)
    {
    	if ($name == 'container' && isset($this->_state[$name]) && is_string($this->_state[$name]->value)) {
			$this->_state[$name]->value = KFactory::tmp('admin::com.files.model.containers')->id($this->_state[$name]->value)->getItem();
    	}
    	else if ($name == 'basepath') {
    		return (string) (isset($this->_state['container']) ? $this->container : '');
    	}

    	return parent::__get($name);
    }
}