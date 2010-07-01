<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesHelperListbox extends ComDefaultHelperListbox
{
	public function departments( $config = array())
	{
		$config['model'] = 'people';
		$config['name']  = 'departments';
		$config['value'] = 'profiles_department_id';
		$config['text']  = 'department';
		
		return parent::_listbox($config);
	}
	
	public function offices( $config = array())
	{
		$config['model'] = 'people';
		$config['name']  = 'offices';
		$config['value'] = 'profiles_office_id';
		$config['text']  = 'office';
		
		return parent::_listbox($config);
	}
	
	public function groups($config = array())
    {
		$config['model'] = 'users';
		$config['value'] = 'gid';
		$config['text']  = 'usertype';
    	
		return parent::_listbox($config);
    }
	
	public function gender( $config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'name'		=> 'gender',
			'state' 	=> null,
			'attribs'	=> array(),
		));
			
		$options = array();
		$options[] = $this->option(array('text' => '- '.JText::_( 'Select Gender' ).' -', 'value' => 0));
		$options[] = $this->option(array('text' => JText::_( 'Male' ) , 'value' => 1 ));
		$options[] = $this->option(array('text' => JText::_( 'Female' ), 'value' => 2 ));

		$list = $this->optionlist(array(
			'options' 	=> $options, 
			'name' 		=> $config->name, 
			'attribs' 	=> $config->attribs, 
			'selected' 	=> $config->state->gender,
		));
		
		return $list;
	}
	
	public function country($config = array())
 	{
 		$config = new KConfig($config);
		$config->append(array(
			'name'		=> 'country',
			'state' 	=> null,
			'attribs'	=> array(),
		));
		
		$list = KFactory::get('admin::com.profiles.model.regions')
			->region('world')
			->getList();
 		
 		$options = array();
 		$options[] =  $this->option(array('text' => '- '. JText::_( 'Select a Country' ) .' -' ));
 			
 		foreach($list as $code => $country) {
 			$options[] = $this->option( array('value' => $code, 'text' => $country));
 		}
 		
 		$list = $this->optionlist(array(
			'options' 	=> $options, 
			'name' 		=> $config->name, 
			'attribs' 	=> $config->attribs, 
			'selected' 	=> $config->state->country,
		));
		
		return $list;
 	}

 	public function state($config = array())
 	{
 		$config = new KConfig($config);
		$config->append(array(
			'name'		=> 'state',
			'state' 	=> null,
			'attribs'	=> array(),
		));
				
 		$list = KFactory::get('admin::com.profiles.model.regions')
			->region($config->state->country)
			->getList();

 		$states   = array();
 		$disabled = false;
 		if(count($list))
 		{
	 		$options[] =  $this->option(array('text' => '- '.JText::_( 'Select a State/Provence' ).' -'  ));
 			foreach($list as $code => $state) {
	 			$options[] = $this->option( array('value' => $code, 'text' => $state));
	 		}
 		} 
 		else 
 		{
 			$options[] =  $this->option(array('text' => '('.JText::_('No states for this country').')'  ));
 			$disabled = true;
 		}

 		$list = $this->optionlist(array(
			'options' 	=> $options, 
			'name' 		=> $config->name, 
			'attribs' 	=> $config->attribs, 
			'selected' 	=> $config->state->state,
 			'disabled'  => $disabled
 		));
		
		return $list;
 	}
}