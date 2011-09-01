<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Settings Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 */

class ComSettingsViewSettingsHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $settings = $this->getModel()->getList();
        
        foreach($settings as $setting) 
        {
	    	if($setting->getType() == 'component' && $setting->getPath()) {
	    	    KFactory::get('joomla:language')->load('com_'.$setting->getName(), JPATH_ADMINISTRATOR);
	    	}
        } 
       
        return parent::display();
    }
}