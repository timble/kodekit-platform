<?php
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