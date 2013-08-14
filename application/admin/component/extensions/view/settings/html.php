<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Settings Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Extensions
 */
class ExtensionsViewSettingsHtml extends Library\ViewHtml
{
    public function render()
    {
        $settings = $this->getModel()->getRowset();
        
        foreach($settings as $setting) 
        {
	    	if($setting->getType() == 'extension' && $setting->getPath()) {
	    	    \JFactory::getLanguage()->load($setting->getName(), JPATH_APPLICATION);
	    	}
        } 
       
        return parent::render();
    }
}