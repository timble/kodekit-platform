<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Settings Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Extensions
 */

class ComExtensionsViewSettingsHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $settings = $this->getModel()->getList();
        
        foreach($settings as $setting) 
        {
	    	if($setting->getType() == 'component' && $setting->getPath()) {
	    	    JFactory::getLanguage()->load($setting->getName(), JPATH_APPLICATION);
	    	}
        } 
       
        return parent::display();
    }
}