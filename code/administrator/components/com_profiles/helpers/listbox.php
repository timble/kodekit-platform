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
		))->append(array(
			'selected'  => $config->{$config->name}
		));
			
		$options = array();
		$options[] = $this->option(array('text' => '- '.JText::_( 'Select Gender' ).' -', 'value' => 0));
		$options[] = $this->option(array('text' => JText::_( 'Male' ) , 'value' => 1 ));
		$options[] = $this->option(array('text' => JText::_( 'Female' ), 'value' => 2 ));

		//Add the options to the config object
		$config->options = $options;
		
		return $this->optionlist($config);
	}
	
	public function country($config = array())
 	{
 		$config = new KConfig($config);
		$config->append(array(
			'name'		=> 'country',
			'state' 	=> null,
			'attribs'	=> array(),
		))->append(array(
			'selected'  => $config->{$config->name}
		));
		
		$list = KFactory::get('admin::com.profiles.model.regions')
			->region('world')
			->getList();
 		
 		$options = array();
 		$options[] =  $this->option(array('text' => '- '. JText::_( 'Select a Country' ) .' -' ));
 			
 		foreach($list as $code => $country) {
 			$options[] = $this->option( array('value' => $code, 'text' => $country));
 		}
 		
 		//Add the options to the config object
		$config->options = $options;
 		
 		return $this->optionlist($config);
 	}

 	public function state($config = array())
 	{
 		$config = new KConfig($config);
		$config->append(array(
			'name'		=> 'state',
			'state' 	=> null,
			'attribs'	=> array(),
		))->append(array(
			'selected'  => $config->{$config->name}
		));
				
 		$list = KFactory::get('admin::com.profiles.model.regions')
			->region($config->country)
			->getList();

 		$options  = array();
 		$disabled = false;
 		if(count($list))
 		{
	 		$options =  $this->option(array('text' => '- '.JText::_( 'Select a State/Provence' ).' -'  ));
 			foreach($list as $code => $state) {
	 			$options[] = $this->option( array('value' => $code, 'text' => $state));
	 		}
 		} 
 		else 
 		{
 			$options[] =  $this->option(array('text' => '('.JText::_('No states for this country').')'  ));
 			$disabled = true;
 		}
 		
 		//Add the options to the config object
		$config->options = $options;

 		return $this->optionlist($config);
 	}
}