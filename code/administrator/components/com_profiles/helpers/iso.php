<?php
/** 
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesHelperIso extends KObject
{
	public function country($name='country', $selected = '')
 	{
 		$countries = array();
 		$countries[] = KTemplate::loadHelper('select.option',  '', '- '. JText::_( 'Select a Country' ) .' -' );
 		
 		$model = KFactory::get('admin::com.profiles.model.regions');
 		$model->getState()->region = 'world';
 		
 		$list = $model->getList();
			
 		foreach($list as $code => $country) {
 			$countries[] = KTemplate::loadHelper('select.option',  $code, $country);
 		}

 		return KTemplate::loadHelper('select.genericlist', $countries, $name, 'class="inputbox" size="1" ', 'value', 'text', $selected );
 	}

 	public function states($region, $name = '', $selected = '' )
 	{
 		$region = strtolower($region);
 		
 		$model = KFactory::get('admin::com.profiles.model.regions');
 		$model->getState()->region = $region;
   
 		$list = $model->getList();

 		$states = array();
 		if(count($list))
 		{
 			$states[] = KTemplate::loadHelper('select.option', '', '- '.JText::_( 'Select a State/Provence' ).' -' );
	 		foreach($list as $code => $state) {
	 			$states[] = KTemplate::loadHelper('select.option',  $code, $state);
	 		}
	 		$disabled = '';
 		} else {
 			$states[] = KTemplate::loadHelper('select.option', '', '('.JText::_('No states for this country').')' );
 			$disabled = ' disabled="disabled"';
 		}

 		return KTemplate::loadHelper('select.genericlist', $states, $name, $disabled.' class="inputbox" size="1" ', 'value', 'text', $selected );
 	}
}