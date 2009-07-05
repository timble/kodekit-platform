<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * Developed for Brian Teeman's Developer Showdown, using Nooku Framework
 * @version		$Id$
 * @package		Beer
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
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
			$active = ($view == strtolower($this->_mixer->getClassName('suffix')) );
			JSubMenuHelper::addEntry(JText::_($title), 'index.php?option=com_beer&view='.$view, $active );
		}
	}

	public function displayMenutitle($title = null)
	{
		$title = $title ? $title : ucfirst($this->_mixer->getClassName('suffix'));
		JToolBarHelper::title( JText::_($title), 'langmanager');
	}

}