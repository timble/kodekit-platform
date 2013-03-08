<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * System Setting Database Row
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class ComExtensionsDatabaseRowSetting_System extends ComExtensionsDatabaseRowSetting
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional Framework\Config object with configuration options.
     * @return void
     */
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
             'name' => 'system',
             'path'	=> JPATH_ROOT.'/config/config.php',
             'data' => JFactory::getConfig()->toArray()
        ));
        
        parent::_initialize($config);
    } 
     
	/**
     * Saves the system configuration
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    public function save()
    {  
        if(!empty($this->_modified)) 
        {
            $config = new JRegistry('config');
            $config->loadArray(JFactory::getConfig()->toArray());
            $config->loadArray($this->_data);
            
		    if (file_put_contents($this->getPath(), $config->toString('PHP', 'config', array('class' => 'JConfig'))) === false) 
		    {
			    $this->setStatusMessage(JText::_('ERRORCONFIGFILE'));
			    $this->setStatus(Framework\Database::STATUS_FAILED);
			
			    return false;
		    }     
		
		    $this->setStatus(Framework\Database::STATUS_UPDATED);
        }
        
        return true;
    }

    /**
     * The setting type
     *
     * @return string 	The setting type
     */
    public function getType()
    {
        return 'system';
    }
}