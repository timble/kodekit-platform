<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Plugin Database Row Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins    
 */
class ComPluginsDatabaseRowPlugin extends KDatabaseRowDefault
{
	/**
	 * Get a value by key
	 *
	 * This method is specialized because of the magic property "description"
	 * which reads from the plugin xml file
	 *
	 * @param   string  The key name.
	 * @return  string  The corresponding value.
	 */
	public function __get($key)
	{
		if($key == 'description') 
		{
		    if(!isset($this->_data['description']) && isset($this->folder, $this->element))
		    {
		        $manifest = JPATH_SITE.'/plugins/'.$this->folder.'/'.$this->element.'.xml';
		        if(file_exists($manifest))
		        {
		            $xml                        = simplexml_load_file($manifest);
			        $this->_data['description'] = $xml->description;   
		        }
		        else  $this->_data['description'] = null;    
		    }
		}

		return parent::__get($key);
	}
}