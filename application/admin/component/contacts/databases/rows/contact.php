<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Contact Database Row Class
 *
 * @author      Isreal Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts   
 */

class ComContactsDatabaseRowContact extends Framework\DatabaseRowTable
{
	public function __get($column)
	{
	    if($column == 'params' && !($this->_data['params']) instanceof JParameter)
        {
	        $file = __DIR__.'/contact.xml';

			$params	= new JParameter($this->_data['params']);
			$params->loadSetupFile($file);

			$this->_data['params'] = $params;
        }
	    
        return parent::__get($column);
	}
	
    public function toArray()
    {
        $data = parent::toArray();

        $data['params'] = $this->params->toArray();
        return $data;
    }
}
