<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Banners Row Class
 *
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
 
class ComBannersDatabaseRowBanner extends KDatabaseRowDefault
{
    public function __get($column)
    {
   	 	if($column == 'params' && !($this->_data['params'] instanceof JParameter))
		{
			$params	= new JParameter($this->_data['params']);
			$this->_data['params'] = $params;
		}
		
		if($column == 'type' && empty($this->_data['type'])) 
		{
		    if (trim($this->custombannercode)) {
		        $type = 'custom';
		    } elseif (preg_match('/.swf$/i',$this->imageurl))  {
		        $type = 'flash';
		    } else {
		        $type = 'image';
		    }
		    
		    $this->_data['type'] = $type;
		}

    	return parent::__get($column);
    }
    
	/**
     * Return an associative array of the data.
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
         
        $data['type']      = $this->type;
        $data['params']    = $this->params->toArray();
     
        return $data;
    }
}