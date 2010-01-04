<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesViewHtml extends ComDefaultViewHtml
{
	/**
	 * Constructor
	 *
	 * @param	array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{
        $options['views'] = array(
			'dashboard' 	=> JText::_('Dashboard'),
			'people' 		=> JText::_('People'),
			'offices' 		=> JText::_('Offices'),
			'departments' 	=> JText::_('Departments'),
			'users'			=> JText::_('Users')
		);
		
		parent::__construct($options);
	}
	
	public function display()
	{
		$name = $this->getName();
		
		//Apend enable and disbale button for all the list views
		if($name != 'dashboard' && KInflector::isPlural($name) && KRequest::type() != 'AJAX')
		{
			KFactory::get('admin::com.profiles.toolbar.'.$name)
				->append('divider')	
				->append('enable')
				->append('disable');	
		}
					
		parent::display();
	}
}