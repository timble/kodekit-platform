<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Regions Model represents countries, as well as states for some countries.
 * $model->setState('region', 'countries|us|au|...')->getList()
 */
class BeerModelRegions extends KModelAbstract
{
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