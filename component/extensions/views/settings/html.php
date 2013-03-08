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
 * Settings Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class ComExtensionsViewSettingsHtml extends ComBaseViewHtml
{
    public function render()
    {
        $settings = $this->getModel()->getRowset();
        
        foreach($settings as $setting) 
        {
	    	if($setting->getType() == 'component' && $setting->getPath()) {
	    	    JFactory::getLanguage()->load($setting->getName(), JPATH_APPLICATION);
	    	}
        } 
       
        return parent::render();
    }
}