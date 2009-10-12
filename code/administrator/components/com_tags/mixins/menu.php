<?php
/**
 * Taxonomy
 * 
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Menubar mixin, can be used in all views to display to main menu
 */
class TagsMixinMenu extends KMixinAbstract
{
	public function displayMenubar()
	{
		$views  = array();
		
		$views['tags'] 	= JText::_('Tags');

		foreach($views as $view => $title)
		{
			$active = ($view == strtolower($this->_mixer->getIdentifier()->name) );
			JSubMenuHelper::addEntry(JText::_($title), 'index.php?option=com_tags&view='.$view, $active );
		}
	}

	public function displayMenutitle($title = null)
	{
		$title = $title ? $title : ucfirst($this->_mixer->getIdentifier()->name);
		JToolBarHelper::title( JText::_($title), 'langmanager');
	}
}