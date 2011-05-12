<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Templates HTML View class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Templates    
 */
class ComTemplatesViewTemplatesHtml extends ComTemplatesViewHtml
{
    public function display()
    {
        $this->getToolbar()
            ->reset()
            ->append('default');

        JSubMenuHelper::addEntry(JText::_('Modules'), 'index.php?option=com_modules&view=modules');
        JSubMenuHelper::addEntry(JText::_('Plugins'), 'index.php?option=com_plugins&view=plugins');
        JSubMenuHelper::addEntry(JText::_('Templates'), 'index.php?option=com_templates&view=templates', true);
        JSubMenuHelper::addEntry(JText::_('Languages'), 'index.php?option=com_languages&view=languages');

		return parent::display();
	}
}