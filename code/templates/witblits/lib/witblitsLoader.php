<?php 
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Templates
 * @subpackage	Witblits
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

class Witblits 
{	
	public static $_timeofday;
	
	/**
	 * Get the time of day
	 *
	 *
	 *
	 * @return	string	da
	 */
	public function getTimeofday()
	{
		if(!isset(self::$_timeofday))
		{
			$user = JFactory::getUser();
			$date = clone JFactory::getDate();

			//Set timezone offset
			if(!$user->guest) $date->setOffset($user->getParam('timezone'));

			$time = $date->toFormat('%H'); 

			$sunrise = date_sunrise($date->toUnix(), SUNFUNCS_RET_DOUBLE); 
			$sunset = date_sunset($date->toUnix(), SUNFUNCS_RET_DOUBLE) + 1; 
			if($time >= $sunrise && $time < $sunrise + 2) $style = 'sunrise'; 
			elseif($time >= $sunrise + 2 && $time < $sunset) $style = 'day'; 
			elseif($time >= $sunset && $time < $sunset + 2) $style = 'sunset'; 
			else $style = 'night';
			
			self::$_timeofday = $style;
		}
		
		return self::$_timeofday;
	}
}