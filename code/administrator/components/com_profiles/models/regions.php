<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Regions Model represents countries, as well as states for some countries.
 * $model->set('region', 'countries|us|au|...')->getList()
 */
class ComProfilesModelRegions extends KModelAbstract
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		// Set the state
		$this->_state->insert('region' , 'word');
	}
	
	public function getList()
	{
		$file = JPATH_COMPONENT_ADMINISTRATOR.DS.'data'.DS.$this->_state->region.'.json';
		if(file_exists($file)) {
			return (array) json_decode(file_get_contents($file));
		} else {
			return array();
		}
	}
}