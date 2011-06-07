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
 * Plugin HTML View class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins    
 */
class ComPluginsViewPluginHtml extends ComPluginsViewHtml
{
    public function display()
    {
        $plugin = $this->getModel()->getItem();
        
        //If both the folder and element is specified, we may load the language file
        if(isset($plugin->type, $plugin->name)) {
            KFactory::get('lib.joomla.language')->load('plg_'.$plugin->type.'_'.$plugin->name, JPATH_ADMINISTRATOR);
        }

		return parent::display();
	}
}