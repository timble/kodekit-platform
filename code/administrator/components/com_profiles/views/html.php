<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ProfilesViewHtml extends KoowaViewHtml
{
	public function display()
	{
		//Get the model
		$model = $this->getModel();
		$name  = $model->getIdentifier()->name;
		
		if(KInflector::isPlural($name))
		{
			$views = array(
				'dashboard' 	=> JText::_('Dashboard'),
				'people' 		=> JText::_('People'),
				'offices' 		=> JText::_('Offices'),
				'departments' 	=> JText::_('Departments'),
				'users'			=> JText::_('Users')
			);
			
			// Mixin a menubar object
			$this->mixin( KFactory::get('admin::com.koowa.mixin.menubar', array('mixer' => $this, 'views' => $views)));
			$this->displayMenubar();
		}
		
		parent::display();
	}
}