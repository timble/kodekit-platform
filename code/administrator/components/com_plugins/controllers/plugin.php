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
 * Plugin Controller Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins    
 */
class ComPluginsControllerPlugin extends ComDefaultControllerDefault
{
    /**
     * Read action
     *
     * This functions loads the language file for a plugin
     *
     *  @return KDatabaseRow    A row object containing the selected row
     */
    protected function _actionRead(KCommandContext $context)
    {
        $plugin = parent::_actionRead($context);
        
        //If both the folder and element is specified, we may load the language file
        if(isset($plugin->folder, $plugin->element)) {
            KFactory::get('lib.joomla.language')->load('plg_'.$plugin->folder.'_'.$plugin->element, JPATH_ADMINISTRATOR);
        }

        return $plugin;
    }
}