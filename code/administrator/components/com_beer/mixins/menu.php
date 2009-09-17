<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * 
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Menubar mixin, can be used in all views to display to main menu
 */
class BeerMixinMenu extends KMixinAbstract
{
	public function displayMenubar()
	{
		$views  = array();

		$views['people'] 		= JText::_('People');
		$views['offices'] 		= JText::_('Offices');
		$views['departments'] 	= JText::_('Departments');

		foreach($views as $view => $title)
		{
			$active = ($view == strtolower($this->_mixer->getIdentifier()->name) );
			JSubMenuHelper::addEntry(JText::_($title), 'index.php?option=com_beer&view='.$view, $active );
		}
	}

	public function displayMenutitle($title = null)
	{
		$title = $title ? $title : ucfirst($this->_mixer->getIdentifier()->name);
		JToolBarHelper::title( JText::_($title), 'langmanager');
	}
}