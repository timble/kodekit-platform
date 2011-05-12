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
 * Plugins HTML View class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins    
 */
class ComPluginsViewPluginsHtml extends ComDefaultViewHtml
{
    /**
     * Return the views output
     * 
     * This function will auto assign the model data to the view if the auto_assign
     * property is set to TRUE. 
     *
     * @return string 	The output of the view
     */
	public function display()
	{
	    $this->getToolbar()
	    				->append('divider')
	    				->append('enable')
	    				->append('disable');

        JSubMenuHelper::addEntry(JText::_('Modules'), 'index.php?option=com_modules&view=modules');
        JSubMenuHelper::addEntry(JText::_('Plugins'), 'index.php?option=com_plugins&view=plugins', true);
        JSubMenuHelper::addEntry(JText::_('Templates'), 'index.php?option=com_templates&view=templates');
        JSubMenuHelper::addEntry(JText::_('Languages'), 'index.php?option=com_languages&view=languages');

		return parent::display();
	}
}