<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * Developed for Brian Teeman's Developer Showdown, using Nooku Framework
 * @version		$Id: departments.php 57 2009-07-05 03:41:14Z shayne $
 * @package		Beer
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */


class BeerModelRegions extends KModelAbstract
{
	public function getList()
	{
		$file = JPATH_COMPONENT_ADMINISTRATOR.DS.'data'.DS.$this->getState('region').'.json';
		if(file_exists($file)) {
			return (array) json_decode(file_get_contents($file));
		} else {
			return array();
		}
	}
}